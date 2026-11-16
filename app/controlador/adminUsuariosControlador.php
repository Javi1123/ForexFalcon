<?php

namespace App\controlador;

use App\modelo\adminUsuariosModelo;
use App\modelo\adminServiciosModelo;

/**
 * Administración de usuarios y actividad (solo Administrador / Superusuario).
 *
 *   GET  /admin                    → panel de resumen
 *   GET  /admin/usuarios           → lista con búsqueda, filtros y paginación
 *   GET  /admin/usuarios/ficha     → JSON con la ficha de un usuario (?correo=)
 *   POST /admin/usuarios/editar    → nombre, apellido, teléfono y país
 *   POST /admin/usuarios/rol       → cambiar el rol
 *   POST /admin/usuarios/eliminar  → borrar la cuenta (solo si no tiene suscripciones)
 *   GET  /admin/actividad          → registro de inicios y cierres de sesión
 *   GET  /admin/actividad/exportar → el mismo registro (con sus filtros) en CSV
 *
 * Quién puede hacer qué (el servidor lo comprueba siempre, aunque el botón no se muestre):
 *   - Superusuario: gestiona a cualquiera, salvo cambiarse el rol o borrarse a sí mismo.
 *   - Administrador: edita y borra solo a usuarios con rol «Usuario», y puede ascenderlos a Administrador.
 *     No puede degradar a otros administradores ni tocar a un Superusuario.
 *   - Nadie puede cambiarse el rol ni borrar su propia cuenta desde aquí (evita quedarse sin administradores).
 *
 * Las acciones POST reutilizan accion() de adminControlador: exige sesión, rol, POST y token CSRF.
 */
class adminUsuariosControlador extends adminControlador {

  private const POR_PAGINA_USUARIOS  = 15;
  private const POR_PAGINA_ACTIVIDAD = 20;

  public function __construct() {
    parent::__construct();
    $this->refrescarRol();
  }

  /**
   * El rol se guarda en la sesión al iniciar sesión. Si un Superusuario degrada a un administrador mientras
   * este tiene la sesión abierta, sin esto seguiría gestionando usuarios hasta cerrar sesión: aquí se vuelve a
   * leer de la base de datos en cada petición de esta sección.
   */
  private function refrescarRol(): void {
    if (empty($_SESSION['email'])) {
      return;
    }
    try {
      $u = adminUsuariosModelo::porCorreo($_SESSION['email']);
      if ($u) {
        $_SESSION['rol'] = $u['Rol'];
      }
    } catch (\Throwable $e) {
      error_log('Admin: no se pudo refrescar el rol: ' . $e->getMessage());
    }
  }

  // ───────────────────────── Vistas ─────────────────────────

  public function panel(): void {
    $this->exigirAdminVista();
    $adminSeccion = 'panel';

    $resumen = $actividadReciente = $registrados = $serie = null;
    $errorDatos = false;
    try {
      $resumen = adminUsuariosModelo::resumen();
      $registrados = adminUsuariosModelo::ultimosRegistrados(5);
      $actividadReciente = adminUsuariosModelo::actividad([], 1, 6)['filas'];
      $serie = adminUsuariosModelo::loginsPorDia(14);
    } catch (\Throwable $e) {
      error_log('Admin panel: ' . $e->getMessage());
      $errorDatos = true;
    }

    [$inicial, $colorAvatar] = $this->avatar();
    $vistaAdmin = true;
    require_once __DIR__ . '/../../app/vista/admin_panel_vista.php';
  }

  public function usuarios(): void {
    $this->exigirAdminVista();
    $adminSeccion = 'usuarios';

    $filtros = [
      'q'      => mb_substr(trim((string) ($_GET['q'] ?? '')), 0, 80),
      'rol'    => in_array($_GET['rol'] ?? '', adminUsuariosModelo::ROLES, true) ? $_GET['rol'] : '',
      'estado' => in_array($_GET['estado'] ?? '', ['en_linea', 'desconectado'], true) ? $_GET['estado'] : '',
      'orden'  => in_array($_GET['orden'] ?? '', ['recientes', 'antiguos', 'nombre', 'acceso'], true) ? $_GET['orden'] : 'recientes',
    ];
    $pagina = max(1, (int) ($_GET['pagina'] ?? 1));

    $lista = ['filas' => [], 'total' => 0];
    $resumen = null;
    $errorDatos = false;
    try {
      $lista = adminUsuariosModelo::listar($filtros, $pagina, self::POR_PAGINA_USUARIOS);
      $resumen = adminUsuariosModelo::resumen();
    } catch (\Throwable $e) {
      error_log('Admin usuarios: ' . $e->getMessage());
      $errorDatos = true;
    }

    $porPagina = self::POR_PAGINA_USUARIOS;
    $totalPaginas = max(1, (int) ceil($lista['total'] / $porPagina));
    if ($pagina > $totalPaginas) { $pagina = $totalPaginas; }

    $rolActor = chatControlador::rolActual();
    $adminCsrf = self::tokenCsrf();
    [$inicial, $colorAvatar] = $this->avatar();
    $vistaAdmin = true;
    require_once __DIR__ . '/../../app/vista/admin_usuarios_vista.php';
  }

  public function actividad(): void {
    $this->exigirAdminVista();
    $adminSeccion = 'actividad';

    $filtros = $this->filtrosActividad();
    $pagina = max(1, (int) ($_GET['pagina'] ?? 1));

    $lista = ['filas' => [], 'total' => 0];
    $resumenFiltro = ['logins' => 0, 'logouts' => 0, 'usuarios' => 0];
    $serie = [];
    $enLinea = 0;
    $errorDatos = false;
    try {
      $lista = adminUsuariosModelo::actividad($filtros, $pagina, self::POR_PAGINA_ACTIVIDAD);
      $resumenFiltro = adminUsuariosModelo::resumenActividad($filtros);
      $serie = adminUsuariosModelo::loginsPorDia(14);
      $enLinea = adminUsuariosModelo::resumen()['en_linea'];
    } catch (\Throwable $e) {
      error_log('Admin actividad: ' . $e->getMessage());
      $errorDatos = true;
    }

    $porPagina = self::POR_PAGINA_ACTIVIDAD;
    $totalPaginas = max(1, (int) ceil($lista['total'] / $porPagina));
    if ($pagina > $totalPaginas) { $pagina = $totalPaginas; }

    [$inicial, $colorAvatar] = $this->avatar();
    $vistaAdmin = true;
    require_once __DIR__ . '/../../app/vista/admin_actividad_vista.php';
  }

  /** CSV con el registro filtrado. Las celdas que empiezan por = + - @ se neutralizan (inyección de fórmulas en Excel). */
  public function exportar(): void {
    $this->exigirAdminVista();

    try {
      $filas = adminUsuariosModelo::actividadParaExportar($this->filtrosActividad());
    } catch (\Throwable $e) {
      error_log('Admin actividad (CSV): ' . $e->getMessage());
      http_response_code(500);
      echo 'No se pudo generar el archivo.';
      exit;
    }

    $neutral = function ($v) {
      $v = (string) $v;
      return ($v !== '' && strpos("=+-@\t\r", $v[0]) !== false) ? "'" . $v : $v;
    };

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="actividad-' . date('Ymd-His') . '.csv"');
    header('Cache-Control: no-store');
    $out = fopen('php://output', 'w');
    fwrite($out, "\xEF\xBB\xBF");                       // BOM: Excel abre bien los acentos
    fputcsv($out, ['Fecha', 'Correo', 'Nombre', 'Evento', 'IP', 'Dispositivo'], ';');
    foreach ($filas as $f) {
      fputcsv($out, [
        $f['Fecha_evento'],
        $neutral($f['Correo']),
        $neutral(trim(($f['Nombre'] ?? '') . ' ' . ($f['Apellido'] ?? ''))),
        $f['Tipo_evento'] === 'login' ? 'Inicio de sesión' : 'Cierre de sesión',
        $neutral($f['Ip_address']),
        adminUsuariosModelo::dispositivo((string) $f['User_agent']),
      ], ';');
    }
    fclose($out);
    exit;
  }

  // ───────────────────────── JSON: ficha y acciones ─────────────────────────

  public function ficha(): void {
    if (empty($_SESSION['email'])) {
      $this->responder(['error' => 'Tu sesión ha caducado. Vuelve a iniciar sesión.'], 401);
    }
    if (!chatControlador::esAdmin()) {
      $this->responder(['error' => 'Solo administradores.'], 403);
    }

    try {
      $correo = $this->correoValido($_GET['correo'] ?? '');
      $ficha = adminUsuariosModelo::ficha($correo);
      if (!$ficha) {
        $this->responder(['ok' => false, 'error' => 'Ese usuario ya no existe.'], 404);
      }
      $ficha['permisos'] = $this->permisos($ficha['usuario']);
      $this->responder(['ok' => true] + $ficha);
    } catch (\InvalidArgumentException $e) {
      $this->responder(['ok' => false, 'error' => $e->getMessage()], 400);
    } catch (\Throwable $e) {
      error_log('Admin ficha: ' . $e->getMessage());
      $this->responder(['ok' => false, 'error' => 'No se pudo leer la ficha.'], 500);
    }
  }

  public function editar(): void {
    $this->accion(function () {
      $objetivo = $this->objetivo();
      if (!$this->permisos($objetivo)['editar']) {
        throw new \DomainException('No tienes permiso para editar a este usuario.');
      }

      $nombre   = $this->campoTexto($_POST['nombre'] ?? '', 'El nombre', 1, 50);
      $apellido = $this->campoTexto($_POST['apellido'] ?? '', 'El apellido', 0, 50);
      $pais     = $this->campoTexto($_POST['pais'] ?? '', 'El país', 1, 50);
      $telefono = trim((string) ($_POST['telefono'] ?? ''));
      if (!preg_match('/^[0-9+()\s-]{6,20}$/', $telefono)) {
        throw new \InvalidArgumentException('El teléfono solo puede tener números, +, espacios, paréntesis y guiones (6 a 20 caracteres).');
      }

      adminUsuariosModelo::actualizarDatos($objetivo['Correo'], $nombre, $apellido, $telefono, $pais);

      // Si el administrador se edita a sí mismo, la sesión refleja el cambio
      if ($this->esElMismo($objetivo['Correo'])) {
        $_SESSION['nombre'] = $nombre;
        $_SESSION['apellido'] = $apellido;
      }
      return ['mensaje' => 'Datos guardados.'];
    });
  }

  public function rol(): void {
    $this->accion(function () {
      $objetivo = $this->objetivo();
      $nuevo = (string) ($_POST['rol'] ?? '');
      if (!in_array($nuevo, adminUsuariosModelo::ROLES, true)) {
        throw new \InvalidArgumentException('Rol no válido.');
      }
      if (!in_array($nuevo, $this->permisos($objetivo)['roles'], true)) {
        throw new \DomainException('No tienes permiso para asignar ese rol a este usuario.');
      }

      adminUsuariosModelo::cambiarRol($objetivo['Correo'], $nuevo);
      return ['mensaje' => 'Rol actualizado a «' . $nuevo . '».', 'rol' => $nuevo];
    });
  }

  public function eliminar(): void {
    $this->accion(function () {
      $objetivo = $this->objetivo();
      if (!$this->permisos($objetivo)['eliminar']) {
        throw new \DomainException('No tienes permiso para eliminar a este usuario.');
      }
      if (adminUsuariosModelo::contarSuscripciones($objetivo['Correo']) > 0) {
        throw new \DomainException('Este usuario tiene suscripciones registradas, así que no se puede eliminar. Si no quieres que entre, cámbiale el rol o contacta con él.');
      }

      adminUsuariosModelo::eliminar($objetivo['Correo']);
      return ['mensaje' => 'Usuario eliminado.'];
    });
  }

  // ───────────────────────── Permisos y validación ─────────────────────────

  /**
   * Lo que el administrador actual puede hacer con $objetivo. Es la ÚNICA fuente de verdad:
   * la usan la ficha (para mostrar u ocultar botones) y cada acción (para permitir o no).
   */
  private function permisos(array $objetivo): array {
    $actor = chatControlador::rolActual();
    $mismo = $this->esElMismo($objetivo['Correo']);
    $rolObj = $objetivo['Rol'];

    $gestiona = $actor === 'Superusuario' || ($actor === 'Administrador' && $rolObj === 'Usuario');

    $roles = [];
    if (!$mismo) {
      if ($actor === 'Superusuario') {
        $roles = adminUsuariosModelo::ROLES;
      } elseif ($actor === 'Administrador' && $rolObj === 'Usuario') {
        $roles = ['Usuario', 'Administrador'];
      }
    }

    return [
      'editar'   => $gestiona || $mismo,
      'roles'    => $roles,
      'eliminar' => !$mismo && $gestiona,
      'es_el_mismo' => $mismo,
    ];
  }

  private function esElMismo(string $correo): bool {
    return strcasecmp($correo, (string) ($_SESSION['email'] ?? '')) === 0;
  }

  /** El usuario sobre el que actúa un POST, leído de la BD (nunca se fía del rol que mande el navegador). */
  private function objetivo(): array {
    $u = adminUsuariosModelo::porCorreo($this->correoValido($_POST['correo'] ?? ''));
    if (!$u) {
      throw new \DomainException('Ese usuario ya no existe.');
    }
    return $u;
  }

  private function correoValido($v): string {
    $v = trim((string) $v);
    if ($v === '' || strlen($v) > 100 || !filter_var($v, FILTER_VALIDATE_EMAIL)) {
      throw new \InvalidArgumentException('Correo no válido.');
    }
    return $v;
  }

  private function campoTexto($v, string $campo, int $min, int $max): string {
    $v = trim(preg_replace('/\s+/u', ' ', (string) $v));
    if (preg_match('/[\x00-\x1F\x7F]/', $v) || mb_strlen($v) < $min || mb_strlen($v) > $max) {
      throw new \InvalidArgumentException($min > 0 ? "$campo es obligatorio (máximo $max caracteres)." : "$campo no puede superar los $max caracteres.");
    }
    return $v;
  }

  private function filtrosActividad(): array {
    $fecha = function ($v) {
      $v = (string) $v;
      $d = \DateTime::createFromFormat('Y-m-d', $v);
      return ($d && $d->format('Y-m-d') === $v) ? $v : '';
    };
    return [
      'q'     => mb_substr(trim((string) ($_GET['q'] ?? '')), 0, 80),
      'tipo'  => in_array($_GET['tipo'] ?? '', ['login', 'logout'], true) ? $_GET['tipo'] : '',
      'desde' => $fecha($_GET['desde'] ?? ''),
      'hasta' => $fecha($_GET['hasta'] ?? ''),
    ];
  }

  // ───────────────────────── Común a las vistas ─────────────────────────

  private function exigirAdminVista(): void {
    if (!isset($_SESSION['email'])) {
      $_SESSION['toast'] = ['tipo' => 'error', 'icono' => 'fa-user-lock', 'titulo' => 'Sin sesión',
                            'mensaje' => 'No has iniciado sesión, así que no puedes entrar en tu zona privada. Inicia sesión para continuar.'];
      header('Location: ' . BASE_PATH . '/acciones?tipo=inicio');
      exit;
    }
    if (!chatControlador::esAdmin()) {
      $_SESSION['toast'] = ['tipo' => 'error', 'mensaje' => 'No tienes permisos para acceder a la administración.'];
      header('Location: ' . BASE_PATH . '/recursos');
      exit;
    }
  }

  /** Inicial y color del avatar que pintan menu_admin.php y cabecera.php (igual que adminControlador::servicios). */
  private function avatar(): array {
    return [
      mb_strtoupper(mb_substr($_SESSION['nombre'], 0, 1)),
      '#' . substr(md5($_SESSION['nombre'] . $_SESSION['apellido']), 0, 6),
    ];
  }
}
