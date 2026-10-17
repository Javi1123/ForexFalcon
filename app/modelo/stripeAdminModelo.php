<?php

namespace App\modelo;

/**
 * Operaciones de ADMINISTRACIÓN sobre los servicios de Stripe.
 * Usa stripeModelo solo para hablar con la API (get/post) y para formatear precios,
 * así la tienda (/recursos) y el panel de administración muestran exactamente lo mismo.
 *
 * Cómo se guarda un servicio en Stripe (igual que lo lee stripeModelo::getServicios):
 *   Producto  → name, description, marketing_features (características), default_price
 *   metadata  → id_servicio (código, p. ej. "AP"), mode ("unico" | "mensual" | ...),
 *               icono (clase de Font Awesome), descripcion_larga
 *
 * Detalles propios de Stripe que explican algunas decisiones de este archivo:
 *  - Un PRECIO no se puede modificar: "cambiar el precio" = crear un precio nuevo, ponerlo como
 *    precio por defecto del producto y archivar el anterior. Quien ya estaba suscrito sigue
 *    pagando el precio antiguo (Stripe no toca las suscripciones existentes).
 *  - Un producto con precios NO se puede borrar: "borrar" = archivarlo (active=false). Deja de
 *    salir en la tienda pero se conserva el historial y se puede restaurar.
 *  - Para dejar vacío un campo se envía una cadena vacía (p. ej. description=).
 */
class stripeAdminModelo {

  /** tipo de precio (como lo ve el administrador) => intervalo de Stripe */
  public const INTERVALOS = ['semanal' => 'week', 'mensual' => 'month', 'anual' => 'year'];

  private stripeModelo $stripe;

  public function __construct(stripeModelo $stripe) {
    $this->stripe = $stripe;
  }

  /** Todos los productos: ['activos' => [...], 'archivados' => [...]] */
  public function listar(): array {
    $salida = ['activos' => [], 'archivados' => []];

    foreach (['activos' => 'true', 'archivados' => 'false'] as $clave => $activo) {
      $respuesta = $this->stripe->get("products?active={$activo}&expand[]=data.default_price&limit=100");
      foreach ($respuesta['data'] as $producto) {
        $salida[$clave][] = $this->mapear($producto);
      }
    }
    return $salida;
  }

  public function obtener(string $productId): array {
    return $this->mapear($this->stripe->get("products/{$productId}?expand[]=default_price"));
  }

  /** Crea producto + precio por defecto en una sola llamada. $d viene ya validado. */
  public function crear(string $codigo, array $d): array {
    $params = [
      'name'     => $d['nombre'],
      'metadata' => [
        'id_servicio' => $codigo,
        'mode'        => $this->modo($d['tipo']),
        'icono'       => $d['icono'],
      ],
      'default_price_data' => [
        'currency'    => self::moneda(),
        'unit_amount' => $d['cents'],
      ],
    ];

    if ($d['corta'] !== '') {
      $params['description'] = $d['corta'];
    }
    if ($d['larga'] !== '') {
      $params['metadata']['descripcion_larga'] = $d['larga'];
    }
    if ($d['tipo'] !== 'unico') {
      $params['default_price_data']['recurring'] = ['interval' => self::INTERVALOS[$d['tipo']]];
    }
    foreach ($d['caracteristicas'] as $i => $texto) {
      $params['marketing_features'][$i] = ['name' => $texto];
    }

    $creado = $this->stripe->post('products', $params);
    return $this->obtener($creado['id']);
  }

  /** Actualiza los datos del producto (NO el precio). */
  public function actualizar(string $productId, array $d, ?string $codigoNuevo = null): void {
    $params = [
      'name'        => $d['nombre'],
      'description' => $d['corta'],                      // '' → Stripe lo deja vacío
      'metadata'    => [
        'icono'             => $d['icono'],
        'descripcion_larga' => $d['larga'],              // '' → Stripe borra esa clave
      ],
      // La lista se REEMPLAZA entera; '' la vacía
      'marketing_features' => '',
    ];

    if ($codigoNuevo !== null) {
      $params['metadata']['id_servicio'] = $codigoNuevo;  // solo si el producto no tenía código
    }
    if ($d['caracteristicas']) {
      $params['marketing_features'] = [];
      foreach ($d['caracteristicas'] as $i => $texto) {
        $params['marketing_features'][$i] = ['name' => $texto];
      }
    }

    $this->stripe->post("products/{$productId}", $params);
  }

  /**
   * Cambia el precio: crea un Price nuevo, lo pone como default_price y archiva el anterior.
   * Devuelve ['price_id' => ..., 'archivado_anterior' => bool].
   */
  public function cambiarPrecio(string $productId, int $cents, string $tipo): array {
    $actual = $this->obtener($productId);

    $params = [
      'product'     => $productId,
      'currency'    => $actual['moneda'] !== '' ? $actual['moneda'] : self::moneda(),
      'unit_amount' => $cents,
    ];
    if ($tipo !== 'unico') {
      $params['recurring'] = ['interval' => self::INTERVALOS[$tipo]];
    }

    $nuevo = $this->stripe->post('prices', $params);

    try {
      // "mode" tiene que casar con el tipo de precio: /pago usa 'payment' si es 'unico' y 'subscription' si no
      $this->stripe->post("products/{$productId}", [
        'default_price' => $nuevo['id'],
        'metadata'      => ['mode' => $this->modo($tipo)],
      ]);
    } catch (\Throwable $e) {
      $this->archivarPrecio($nuevo['id']);               // no dejar un precio huérfano
      throw $e;
    }

    $archivado = $actual['price_id'] !== '' && $this->archivarPrecio($actual['price_id']);
    return ['price_id' => $nuevo['id'], 'archivado_anterior' => $archivado];
  }

  /** Archiva (false) o restaura (true) un producto. */
  public function cambiarActivo(string $productId, bool $activo): void {
    $this->stripe->post("products/{$productId}", ['active' => $activo ? 'true' : 'false']);
  }

  // ───────────────────────── Ayudas ─────────────────────────

  public static function moneda(): string {
    return defined('CURRENCY') ? CURRENCY : 'eur';
  }

  public function modo(string $tipo): string {
    return $tipo === 'unico' ? 'unico' : $tipo;
  }

  /** Mejor esfuerzo: si no se puede archivar el precio viejo no se considera un fallo. */
  private function archivarPrecio(string $priceId): bool {
    try {
      $this->stripe->post("prices/{$priceId}", ['active' => 'false']);
      return true;
    } catch (\Throwable $e) {
      error_log('No se pudo archivar el precio ' . $priceId . ': ' . $e->getMessage());
      return false;
    }
  }

  /** Producto de Stripe → array que usan la vista y el JS del panel de administración. */
  private function mapear(array $producto): array {
    $price = $producto['default_price'] ?? null;
    $price = is_array($price) ? $price : null;

    $tipo = 'unico';
    $importe = '';
    $precio = 'Sin precio';
    $periodo = '';

    if ($price && isset($price['unit_amount'])) {
      if (($price['type'] ?? '') === 'recurring') {
        $intervalo = $price['recurring']['interval'] ?? '';
        $tipo = array_search($intervalo, self::INTERVALOS, true) ?: $intervalo;
      }
      $importe = number_format($price['unit_amount'] / 100, 2, ',', '');
      $precio  = $this->stripe->formatearPrecio($price);
      $periodo = $this->stripe->formatearPeriodo($price);
    }

    $meta = $producto['metadata'] ?? [];

    return [
      'product_id'        => $producto['id'],
      'id_servicio'       => $meta['id_servicio'] ?? '',
      'mode'              => $meta['mode'] ?? '',
      'icono'             => $meta['icono'] ?? 'fa-solid fa-star',
      'nombre'            => $producto['name'] ?? '',
      'descripcion_corta' => $producto['description'] ?? '',
      'descripcion_larga' => $meta['descripcion_larga'] ?? '',
      'caracteristicas'   => array_map(fn($f) => $f['name'], $producto['marketing_features'] ?? []),
      'precio'            => $precio,
      'periodo'           => $periodo,
      'tipo'              => $tipo,
      'importe'           => $importe,
      'moneda'            => $price['currency'] ?? '',
      'price_id'          => $price['id'] ?? '',
      'activo'            => (bool) ($producto['active'] ?? false),
    ];
  }
}
