<?php

namespace App\controlador;

use App\core\DB;
use App\modelo\stripeModelo;
use App\modelo\stripeAdminModelo;
use App\modelo\adminServiciosModelo;

/**
 * Administración de servicios (solo Administrador / Superusuario).
 *
 *   GET  /admin             → la vista (casi igual que /recursos, con botones de gestión)
 *   POST /admin/crear       → nuevo servicio (producto + precio en Stripe, fila en Servicios)
 *   POST /admin/editar      → nombre, descripciones, icono, características
 *   POST /admin/precio      → cambiar precio / tipo de cobro
 *   POST /admin/estado      → archivar (activo=0) o restaurar (activo=1)
 *   POST /admin/sincronizar → crea/actualiza la fila de la BD a partir de Stripe
 *
 * Reglas de seguridad:
 *  - Hace falta sesión y rol de administrador, tanto en la vista como en cada acción.
 *  - Las acciones exigen POST y el token CSRF (cabecera X-CSRF-Token).
 *  - Todo lo que llega se valida y se normaliza ANTES de tocar Stripe o la BD.
 *  - El id de producto de Stripe se valida con una expresión estricta antes de usarlo en una URL.
 *
 * Cada operación toca Stripe y la tabla Servicios a la vez (ver adminServiciosModelo). Si algo
 * falla, se deshace lo que se pueda para no dejar a medias ninguno de los dos lados.
 */
class adminControlador {

  private const TIPOS = ['unico', 'semanal', 'mensual', 'anual'];

  public function __construct() {
    // Nombres con acentos: fuerza utf8mb4 solo en las peticiones de administración (sin tocar DB.php).
    DB::getInstance()->exec("SET NAMES utf8mb4");
  }

  public static function tokenCsrf(): string {
    if (empty($_SESSION['admin_csrf'])) {
      $_SESSION['admin_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['admin_csrf'];
  }

  // ───────────────────────── Vista ─────────────────────────

  public function servicios(): void {
    if (!isset($_SESSION['email'])) {
      header('Location: ' . BASE_PATH . '/acciones?tipo=inicio');
      exit;
    }
    if (!chatControlador::esAdmin()) {
      $_SESSION['toast'] = ['tipo' => 'error', 'mensaje' => 'No tienes permisos para acceder a la administración.'];
      header('Location: ' . BASE_PATH . '/recursos');
      exit;
    }

    // Variables que usan menu.php y cabecera.php (igual que principalControlador::recursos)
    $inicial = mb_strtoupper(mb_substr($_SESSION['nombre'], 0, 1));
    $colorAvatar = '#' . substr(md5($_SESSION['nombre'] . $_SESSION['apellido']), 0, 6);
    $vistaAdmin = true;

    $activos = [];
    $archivados = [];
    $errorStripe = null;
    try {
      $lista = $this->stripeAdmin()->listar();
      $activos = $lista['activos'];
      $archivados = $lista['archivados'];
    } catch (\Throwable $e) {
      error_log('Admin servicios: ' . $e->getMessage());
      $errorStripe = $this->mensajeError($e);
    }

    $sync = adminServiciosModelo::existentes();
    $modoPrueba = strpos(STRIPE_SECRET_KEY, 'sk_test_') === 0;
    $adminCsrf = self::tokenCsrf();

    require_once __DIR__ . '/../../app/vista/admin_servicios_vista.php';
  }

  // ───────────────────────── Acciones ─────────────────────────

  public function crear(): void {
    $this->accion(function () {
      $d = $this->validar($_POST, true);
      $admin = $this->stripeAdmin();

      $usados = $this->codigosUsados($admin->listar());
      $codigo = $d['codigo'] !== '' ? $d['codigo'] : $this->generarCodigo($d['nombre'], $usados);
      if (in_array($codigo, $usados, true)) {
        throw new \DomainException("Ya existe un servicio con el código {$codigo}. Elige otro.");
      }

      $metodo = adminServiciosModelo::metodoDesdeTipo($d['tipo']);
      $producto = $this->transaccion(
        function () use ($codigo, $d, $metodo) { adminServiciosModelo::insertar($codigo, $d['nombre'], $d['corta'], $metodo); },
        function () use ($admin, $codigo, $d) { return $admin->crear($codigo, $d); },
        // si Stripe llegó a crearlo pero la BD falló al confirmar, se archiva para no dejarlo a la venta
        function ($creado) use ($admin) { $admin->cambiarActivo($creado['product_id'], false); }
      );

      return ['mensaje' => 'Servicio creado.', 'producto' => $producto];
    });
  }

  public function editar(): void {
    $this->accion(function () {
      $pid = $this->productId($_POST['product_id'] ?? '');
      $d = $this->validar($_POST, false);
      $admin = $this->stripeAdmin();
      $actual = $admin->obtener($pid);

      // El código lo manda Stripe, NUNCA el navegador (cambiarlo huérfana las suscripciones existentes).
      $codigo = strtoupper($actual['id_servicio']);
      $codigoNuevo = null;
      if ($codigo === '') {
        $codigo = $codigoNuevo = $this->generarCodigo($d['nombre'], $this->codigosUsados($admin->listar()));
      }

      $metodo = adminServiciosModelo::metodoDesdeTipo($actual['tipo']);
      $this->transaccion(
        function () use ($codigo, $d, $metodo, $actual) {
          adminServiciosModelo::guardar($codigo, $d['nombre'], $d['corta'], $metodo, $actual['activo'] ? 1 : 0);
        },
        function () use ($admin, $pid, $d, $codigoNuevo) { $admin->actualizar($pid, $d, $codigoNuevo); }
      );

      return ['mensaje' => 'Cambios guardados.', 'producto' => $admin->obtener($pid)];
    });
  }

  public function precio(): void {
    $this->accion(function () {
      $pid = $this->productId($_POST['product_id'] ?? '');
      $tipo = $this->tipo($_POST['tipo'] ?? '');
      $cents = $this->centimos($this->texto($_POST, 'importe'));

      $admin = $this->stripeAdmin();
      $actual = $admin->obtener($pid);

      if ($actual['tipo'] === $tipo && (int) str_replace(',', '', $actual['importe']) === $cents) {
        return ['mensaje' => 'El precio ya era ese: no se ha cambiado nada.', 'sin_cambios' => true, 'producto' => $actual];
      }

      $codigo = $this->codigoObligatorio($actual);
      $metodo = adminServiciosModelo::metodoDesdeTipo($tipo);
      $resultado = $this->transaccion(
        function () use ($codigo, $actual, $metodo) {
          adminServiciosModelo::guardar($codigo, $actual['nombre'], $actual['descripcion_corta'], $metodo, $actual['activo'] ? 1 : 0);
        },
        function () use ($admin, $pid, $cents, $tipo) { return $admin->cambiarPrecio($pid, $cents, $tipo); }
      );

      return [
        'mensaje'            => 'Precio actualizado. Quien ya estaba suscrito mantiene su precio anterior.',
        'archivado_anterior' => $resultado['archivado_anterior'],
        'producto'           => $admin->obtener($pid),
      ];
    });
  }

  public function estado(): void {
    $this->accion(function () {
      $pid = $this->productId($_POST['product_id'] ?? '');
      $activo = ($_POST['activo'] ?? '') === '1';

      $admin = $this->stripeAdmin();
      $actual = $admin->obtener($pid);
      $codigo = $this->codigoObligatorio($actual);
      $metodo = adminServiciosModelo::metodoDesdeTipo($actual['tipo']);

      $this->transaccion(
        function () use ($codigo, $actual, $metodo, $activo) {
          adminServiciosModelo::guardar($codigo, $actual['nombre'], $actual['descripcion_corta'], $metodo, $activo ? 1 : 0);
        },
        function () use ($admin, $pid, $activo) { $admin->cambiarActivo($pid, $activo); }
      );

      return ['mensaje' => $activo ? 'Servicio restaurado: vuelve a estar en la tienda.' : 'Servicio archivado: ya no aparece en la tienda.'];
    });
  }

  public function sincronizar(): void {
    $this->accion(function () {
      $pid = $this->productId($_POST['product_id'] ?? '');
      $actual = $this->stripeAdmin()->obtener($pid);
      $codigo = $this->codigoObligatorio($actual);

      adminServiciosModelo::guardar(
        $codigo, $actual['nombre'], $actual['descripcion_corta'],
        adminServiciosModelo::metodoDesdeTipo($actual['tipo']), $actual['activo'] ? 1 : 0
      );

      return ['mensaje' => 'Servicio sincronizado con la base de datos.'];
    });
  }

  // ───────────────────────── Validación ─────────────────────────

  /** Devuelve los datos normalizados o lanza \InvalidArgumentException con un mensaje para el administrador. */
  private function validar(array $in, bool $conPrecio): array {
    $nombre = $this->texto($in, 'nombre');
    if ($nombre === '') {
      throw new \InvalidArgumentException('El nombre es obligatorio.');
    }
    if (mb_strlen($nombre) > 100) {
      throw new \InvalidArgumentException('El nombre no puede pasar de 100 caracteres.');
    }

    $corta = $this->texto($in, 'descripcion_corta');
    if (mb_strlen($corta) > 300) {
      throw new \InvalidArgumentException('La descripción corta no puede pasar de 300 caracteres.');
    }

    // Stripe limita cada valor de metadata a 500 caracteres
    $larga = $this->texto($in, 'descripcion_larga');
    if (mb_strlen($larga) > 500) {
      throw new \InvalidArgumentException('La descripción larga no puede pasar de 500 caracteres.');
    }

    // Se imprime tal cual en class="..." de la tienda: solo se admiten clases de Font Awesome
    $icono = $this->texto($in, 'icono') ?: 'fa-solid fa-star';
    if (strlen($icono) > 60 || !preg_match('/^fa-(solid|regular|brands) fa-[a-z0-9]+(?:-[a-z0-9]+)*$/', $icono)) {
      throw new \InvalidArgumentException('El icono no es válido.');
    }

    // Stripe: máximo 15 características y 80 caracteres cada una
    $caracteristicas = [];
    foreach (preg_split('/\R/u', $this->texto($in, 'caracteristicas')) as $linea) {
      $linea = trim($linea);
      if ($linea === '') {
        continue;
      }
      if (mb_strlen($linea) > 80) {
        throw new \InvalidArgumentException('Cada característica puede tener como máximo 80 caracteres.');
      }
      $caracteristicas[] = $linea;
    }
    if (count($caracteristicas) > 15) {
      throw new \InvalidArgumentException('Como máximo 15 características.');
    }

    $codigo = strtoupper($this->texto($in, 'id_servicio'));
    if ($codigo !== '' && !preg_match('/^[A-Z0-9]{1,20}$/', $codigo)) {
      throw new \InvalidArgumentException('El código solo puede tener letras y números (máximo 20).');
    }

    $datos = [
      'nombre' => $nombre, 'corta' => $corta, 'larga' => $larga, 'icono' => $icono,
      'caracteristicas' => $caracteristicas, 'codigo' => $codigo, 'tipo' => 'unico', 'cents' => 0,
    ];

    if ($conPrecio) {
      $datos['tipo'] = $this->tipo($in['tipo'] ?? '');
      $datos['cents'] = $this->centimos($this->texto($in, 'importe'));
    }
    return $datos;
  }

  private function tipo($valor): string {
    if (!is_string($valor) || !in_array($valor, self::TIPOS, true)) {
      throw new \InvalidArgumentException('Elige el tipo de cobro: pago único, semanal, mensual o anual.');
    }
    return $valor;
  }

  /** "49", "49.5" o "49,90" → céntimos (sin usar decimales flotantes). Mínimo 0,50 €. */
  private function centimos(string $texto): int {
    $texto = str_replace(' ', '', $texto);
    if (!preg_match('/^(\d{1,6})(?:[.,](\d{1,2}))?$/', $texto, $m)) {
      throw new \InvalidArgumentException('El precio no es válido. Escribe un número, por ejemplo 49 o 49,90.');
    }
    $cents = ((int) $m[1]) * 100 + (int) str_pad($m[2] ?? '0', 2, '0');
    if ($cents < 50) {
      throw new \InvalidArgumentException('El precio mínimo es 0,50.');
    }
    return $cents;
  }

  private function productId($valor): string {
    if (!is_string($valor) || !preg_match('/^prod_[A-Za-z0-9]{1,64}$/', $valor)) {
      throw new \InvalidArgumentException('Producto no válido.');
    }
    return $valor;
  }

  private function codigoObligatorio(array $producto): string {
    $codigo = strtoupper($producto['id_servicio']);
    if ($codigo === '') {
      throw new \DomainException('Este producto de Stripe no tiene código (id_servicio). Edítalo y guárdalo para asignarle uno.');
    }
    return $codigo;
  }

  private function texto(array $in, string $clave): string {
    return (isset($in[$clave]) && is_string($in[$clave])) ? trim($in[$clave]) : '';
  }

  // ───────────────────────── Códigos de servicio ─────────────────────────

  /** Códigos ya ocupados (en mayúsculas), tanto en Stripe —activos y archivados— como en la BD. */
  private function codigosUsados(array $lista): array {
    $usados = array_keys(adminServiciosModelo::existentes());
    foreach (array_merge($lista['activos'], $lista['archivados']) as $s) {
      if ($s['id_servicio'] !== '') {
        $usados[] = strtoupper($s['id_servicio']);
      }
    }
    return array_values(array_unique($usados));
  }

  /** "Análisis Avanzado" → "AA" (y si está cogido, "AA2", "AA3"...). */
  private function generarCodigo(string $nombre, array $usados): string {
    $sinTildes = strtr(mb_strtolower($nombre), [
      'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'ñ' => 'n',
    ]);
    $base = '';
    foreach (preg_split('/[^a-z0-9]+/', $sinTildes, -1, PREG_SPLIT_NO_EMPTY) as $palabra) {
      $base .= strtoupper($palabra[0]);
    }
    $base = substr($base ?: 'SRV', 0, 4);

    $codigo = $base;
    for ($n = 2; in_array($codigo, $usados, true); $n++) {
      $codigo = $base . $n;
    }
    return $codigo;
  }

  // ───────────────────────── Infraestructura ─────────────────────────

  private function stripeAdmin(): stripeAdminModelo {
    return new stripeAdminModelo(new stripeModelo(STRIPE_SECRET_KEY));
  }

  /**
   * Hace el cambio en la BD (sin confirmar), luego en Stripe, y solo entonces confirma la BD.
   * Si Stripe falla, la BD se deshace sola. $compensar permite deshacer Stripe si fallara la confirmación.
   */
  private function transaccion(callable $local, callable $stripe, ?callable $compensar = null) {
    $pdo = DB::getInstance();
    $pdo->beginTransaction();
    $resultado = null;
    $hechoEnStripe = false;

    try {
      $local();
      $resultado = $stripe();
      $hechoEnStripe = true;
      $pdo->commit();
      return $resultado;
    } catch (\Throwable $e) {
      if ($pdo->inTransaction()) {
        $pdo->rollBack();
      }
      if ($hechoEnStripe && $compensar) {
        try {
          $compensar($resultado);
        } catch (\Throwable $x) {
          error_log('No se pudo deshacer el cambio en Stripe: ' . $x->getMessage());
        }
      }
      throw $e;
    }
  }

  /** Comprueba sesión + rol + POST + CSRF, ejecuta la acción y responde JSON. */
  private function accion(callable $accion): void {
    if (empty($_SESSION['email'])) {
      $this->responder(['error' => 'Tu sesión ha caducado. Vuelve a iniciar sesión.'], 401);
    }
    if (!chatControlador::esAdmin()) {
      $this->responder(['error' => 'Solo administradores.'], 403);
    }
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
      $this->responder(['error' => 'Método no permitido.'], 405);
    }
    $esperado = $_SESSION['admin_csrf'] ?? '';
    $recibido = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if ($esperado === '' || !hash_equals($esperado, $recibido)) {
      $this->responder(['error' => 'Petición no válida. Recarga la página.'], 403);
    }

    try {
      $this->responder(['ok' => true] + $accion());
    } catch (\InvalidArgumentException $e) {
      $this->responder(['ok' => false, 'error' => $e->getMessage()], 400);
    } catch (\DomainException $e) {
      $this->responder(['ok' => false, 'error' => $e->getMessage()], 409);
    } catch (\Throwable $e) {
      error_log('Admin servicios: ' . get_class($e) . ': ' . $e->getMessage());
      $this->responder(['ok' => false, 'error' => $this->mensajeError($e)], 502);
    }
  }

  /** Mensaje apto para mostrar al administrador sin filtrar detalles internos. */
  private function mensajeError(\Throwable $e): string {
    if ($e instanceof \PDOException) {
      return 'No se pudo guardar en la base de datos.';
    }
    if ($e instanceof \Exception) {
      return $e->getMessage();   // viene de stripeModelo: "Stripe devolvió un error: ..." / "Error de conexión con Stripe: ..."
    }
    return 'No se pudo conectar con Stripe. Inténtalo de nuevo en unos segundos.';
  }

  private function responder(array $datos, int $codigo = 200): void {
    http_response_code($codigo);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit;
  }
}
