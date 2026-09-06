<?php
/**
 * StripeClient.php
 * ---------------------------------------------------------
 * Cliente muy ligero para hablar con la API REST de Stripe
 * usando cURL, sin necesidad de instalar el SDK oficial vía
 * Composer. Si prefieres el SDK oficial, instala:
 *   composer require stripe/stripe-php
 * y sustituye estas funciones por las clases de la librería.
 * ---------------------------------------------------------
 */

namespace App\modelo;

use App\core\DB;

class stripeModelo {
  private string $secretKey;
  private string $apiBase = 'https://api.stripe.com/v1';

  public function __construct(string $secretKey)
  {
    $this->secretKey = $secretKey;
  }

  /**
   * Hace una petición POST a la API de Stripe.
   * Stripe espera los datos en formato application/x-www-form-urlencoded,
   * incluyendo arrays anidados tipo line_items[0][price_data][...].
   */
  public function post(string $endpoint, array $data): array
  {
    $ch = curl_init("{$this->apiBase}/{$endpoint}");

    curl_setopt_array($ch, [
      CURLOPT_RETURNTRANSFER => true,   // devuelve la respuesta como string, en vez de imprimirla directamente
      CURLOPT_POST           => true,   // usa el método HTTP POST
      CURLOPT_POSTFIELDS     => http_build_query($data), // convierte el array $data en formato x-www-form-urlencoded
      CURLOPT_HTTPHEADER     => [
        'Authorization: Bearer ' . $this->secretKey,  // autentica la petición con tu clave secreta de Stripe
      ],
      CURLOPT_TIMEOUT        => 30,     // si Stripe no responde en 30s, aborta
    ]);

    $response = curl_exec($ch);              // hace la llamada real y guarda la respuesta (JSON en texto)
    $error    = curl_error($ch);             // si hubo un error de RED (no de Stripe), aquí queda el mensaje
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // código HTTP de la respuesta (200, 400, 401...)
    curl_close($ch);                         // libera los recursos de la petición

    if ($error) {
      throw new \Exception("Error de conexión con Stripe: {$error}");
    }

    $decoded = json_decode($response, true);

    if ($httpCode >= 400) {
      $mensaje = $decoded['error']['message'] ?? 'Error desconocido de Stripe';
      throw new \Exception("Stripe devolvió un error: {$mensaje}");
    }

    return $decoded;
  }

  public function get(string $endpoint): array
  {
    $ch = curl_init("{$this->apiBase}/{$endpoint}");

    curl_setopt_array($ch, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER     => [
        'Authorization: Bearer ' . $this->secretKey,
      ],
      CURLOPT_TIMEOUT        => 30,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $decoded = json_decode($response, true);

    if ($httpCode >= 400) {
      $mensaje = $decoded['error']['message'] ?? 'Error desconocido de Stripe';
      throw new \Exception("Stripe devolvió un error: {$mensaje}");
    }

    return $decoded;
  }

  /**
   * Trae todos los productos activos de Stripe y los transforma
   * al formato que espera recursos_vista.php (nombre, precio,
   * periodo, descripciones, características, etc).
   */
  public function getServicios(): array
  {
    $respuesta = $this->get('products?active=true&expand[]=data.default_price&limit=100');

    $servicios = [];

    foreach ($respuesta['data'] as $producto) {
      $price = $producto['default_price'] ?? null;

      if (!$price) {
        continue; // producto sin precio por defecto, se salta
      }

      $servicios[] = [
        'mode'              => $producto['metadata']['mode'] ?? $producto['id'],
        'icono'             => $producto['metadata']['icono'] ?? 'fa-solid fa-star',
        'nombre'            => $producto['name'],
        'precio'            => $this->formatearPrecio($price),
        'periodo'           => $this->formatearPeriodo($price),
        'descripcion_corta' => $producto['description'] ?? '',
        'descripcion_larga' => $producto['metadata']['descripcion_larga'] ?? ($producto['description'] ?? ''),
        'caracteristicas'   => array_map(
          fn($f) => $f['name'],
          $producto['marketing_features'] ?? []
        ),
        // Necesario en /pago para crear la Checkout Session:
        // 'line_items[0][price]' => $servicio['price_id']
        'price_id'          => $price['id'],
        'product_id'          => $producto['id'],
      ];
    }

    return $servicios;
  }

  /**
   * Convierte el importe en céntimos de Stripe a un texto tipo "49€".
   */
  private function formatearPrecio(array $price): string
  {
    $simbolos = ['eur' => '€', 'usd' => '$', 'gbp' => '£'];
    $simbolo  = $simbolos[$price['currency']] ?? strtoupper($price['currency']);
    $importe  = $price['unit_amount'] / 100;

    // Sin decimales si es un número entero (49 en vez de 49,00)
    $formateado = ($importe == (int) $importe)
      ? (int) $importe
      : number_format($importe, 2, ',', '.');

    return $formateado . $simbolo;
  }

  /**
   * Convierte el tipo/intervalo del precio en un texto tipo "/mes"
   * o "pago único".
   */
  private function formatearPeriodo(array $price): string
  {
    if ($price['type'] !== 'recurring') {
      return 'pago único';
    }

    $intervalos = [
      'day'   => '/día',
      'week'  => '/semana',
      'month' => '/mes',
      'year'  => '/año',
    ];

    return $intervalos[$price['recurring']['interval']] ?? '/' . $price['recurring']['interval'];
  }

  public static function getServiciosUsuario ($email) {
    $pdo = DB::getInstance();
    $stmt = $pdo->prepare("SELECT * FROM suscripciones WHERE Correo = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }
}