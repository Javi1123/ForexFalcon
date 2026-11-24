<?php

namespace App\controlador;

use App\core\Contrasena;
use App\core\Paises;
use App\modelo\adminUsuariosModelo;
use App\modelo\configuracionModelo;
use App\modelo\correoModelo;

/**
 * Ventana "Configuración" del menú del perfil. Es la misma para usuarios y administradores: cada persona
 * solo puede ver y cambiar SU cuenta (el correo sale siempre de la sesión, nunca de lo que envíe el navegador).
 * Responde JSON. Las peticiones que cambian algo exigen el token CSRF de la sesión en la cabecera X-CSRF-Token.
 *
 *   GET  /configuracion/datos              → datos del formulario + si el correo está verificado
 *   POST /configuracion/guardar            → nombre, apellido, teléfono y país
 *   POST /configuracion/contrasena         → cambio de contraseña (pide la actual)
 *   POST /configuracion/correo/enviar      → envía un código de 6 dígitos al correo
 *   POST /configuracion/correo/confirmar   → comprueba el código
 */
class configuracionControlador {

  private const MAX_FALLOS_CONTRASENA = 5;     // contraseñas actuales erróneas permitidas...
  private const VENTANA_FALLOS        = 600;   // ...en estos segundos

  /** Token CSRF de esta sesión (lo imprime recursos/pie.php para que lo use el JavaScript). */
  public static function tokenCsrf(): string {
    if (empty($_SESSION['cfg_csrf'])) {
      $_SESSION['cfg_csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['cfg_csrf'];
  }

  // ───────────────────────── Endpoints ─────────────────────────

  public function datos(): void {
    $this->accion('GET', function (string $correo) {
      $p = configuracionModelo::perfil($correo);
      if (!$p) {
        throw new \DomainException('No encontramos tu cuenta.');
      }
      return [
        'perfil' => [
          'nombre'   => $p['Nombre'],
          'apellido' => $p['Apellido'],
          'email'    => $p['Correo'],
          'telefono' => $p['Telefono'],
          'pais'     => $p['Pais'],
          'rol'      => $p['Rol'],
        ],
        'correo_verificado'   => configuracionModelo::correoVerificado($correo),
      ];
    });
  }

  public function guardar(): void {
    $this->accion('POST', function (string $correo) {
      $nombre   = $this->nombre($_POST['nombre'] ?? '', 'nombre');
      $apellido = $this->nombre($_POST['apellido'] ?? '', 'apellido');
      $telefono = trim((string) ($_POST['telefono'] ?? ''));
      $pais     = (string) ($_POST['pais'] ?? '');

      if (!preg_match('/^\d{9,15}$/', $telefono)) {
        throw new \InvalidArgumentException('El teléfono debe tener entre 9 y 15 dígitos, sin espacios ni símbolos.');
      }
      if (!Paises::existe($pais)) {
        throw new \InvalidArgumentException('Selecciona un país de la lista.');
      }

      adminUsuariosModelo::actualizarDatos($correo, $nombre, $apellido, $telefono, $pais);

      // La sesión (menú, saludo...) refleja el cambio sin tener que volver a entrar
      $_SESSION['nombre']   = $nombre;
      $_SESSION['apellido'] = $apellido;

      return ['mensaje' => 'Datos guardados correctamente.', 'nombre_completo' => $nombre . ' ' . $apellido];
    });
  }

  public function contrasena(): void {
    $this->accion('POST', function (string $correo) {
      $actual = (string) ($_POST['contrasena_actual'] ?? '');
      $nueva  = (string) ($_POST['contrasena_nueva'] ?? '');
      $repite = (string) ($_POST['contrasena_repetir'] ?? '');

      // Freno a quien intente adivinar la contraseña actual desde una sesión abierta (p. ej. un ordenador desatendido)
      $ahora  = time();
      $fallos = array_values(array_filter($_SESSION['cfg_pass_fallos'] ?? [], fn($t) => $ahora - $t < self::VENTANA_FALLOS));
      if (count($fallos) >= self::MAX_FALLOS_CONTRASENA) {
        throw new \OverflowException('Demasiados intentos con la contraseña actual. Espera unos minutos e inténtalo de nuevo.');
      }

      if ($actual === '') {
        throw new \InvalidArgumentException('Escribe tu contraseña actual.');
      }

      $guardado = configuracionModelo::hashContrasena($correo);
      if ($guardado === null || !Contrasena::verificar($actual, $guardado)) {
        $fallos[] = $ahora;
        $_SESSION['cfg_pass_fallos'] = $fallos;
        throw new \DomainException('La contraseña actual no es correcta.');
      }

      if (($error = Contrasena::errorDePolitica($nueva)) !== null) {
        throw new \InvalidArgumentException('La contraseña nueva no es válida. ' . $error);
      }
      if ($nueva !== $repite) {
        throw new \InvalidArgumentException('Las dos contraseñas nuevas no coinciden.');
      }
      if ($nueva === $actual) {
        throw new \InvalidArgumentException('La contraseña nueva debe ser distinta de la actual.');
      }

      configuracionModelo::cambiarContrasena($correo, $nueva);
      unset($_SESSION['cfg_pass_fallos']);

      // Nuevo identificador de sesión tras un cambio de credenciales
      session_regenerate_id(true);

      try {
        correoModelo::correo_contrasena_cambiada($correo, $_SESSION['nombre'] ?? '');
      } catch (\Throwable $e) {
        error_log('Aviso de cambio de contraseña: ' . $e->getMessage());
      }

      return ['mensaje' => 'Contraseña actualizada. Te hemos enviado un correo de confirmación.'];
    });
  }

  public function correoEnviar(): void {
    $this->accion('POST', function (string $correo) {
      if (configuracionModelo::correoVerificado($correo)) {
        return ['mensaje' => 'Tu correo ya está verificado.', 'verificado' => true];
      }

      $r = configuracionModelo::crearCodigo($correo);
      if (isset($r['error'])) {
        if ($r['error'] === 'espera') {
          throw new \OverflowException('Espera ' . (int) $r['segundos'] . ' segundos antes de pedir otro código.');
        }
        throw new \OverflowException('Has pedido demasiados códigos. Inténtalo de nuevo dentro de una hora.');
      }

      $enviado = correoModelo::correo_codigo_verificacion($correo, $_SESSION['nombre'] ?? '', $r['codigo'], configuracionModelo::MINUTOS_CODIGO);

      if (!$enviado) {
        // Solo en desarrollo (localhost suele no tener servidor de correo) el código queda en el log de PHP
        if (strpos((string) (defined('SITE_URL') ? SITE_URL : ''), '://localhost') !== false) {
          error_log('[DESARROLLO] mail() no pudo enviar. Código de verificación para ' . $correo . ': ' . $r['codigo']);
        }
        throw new \RuntimeException('No hemos podido enviar el correo. Inténtalo de nuevo en unos minutos.');
      }

      return ['mensaje' => 'Te hemos enviado un código a ' . $correo . '. Caduca en ' . configuracionModelo::MINUTOS_CODIGO . ' minutos.'];
    });
  }

  public function correoConfirmar(): void {
    $this->accion('POST', function (string $correo) {
      $codigo = trim((string) ($_POST['codigo'] ?? ''));
      if (!preg_match('/^\d{6}$/', $codigo)) {
        throw new \InvalidArgumentException('El código tiene 6 dígitos.');
      }

      switch (configuracionModelo::comprobarCodigo($correo, $codigo)) {
        case 'ok':
          return ['mensaje' => '¡Correo verificado!', 'verificado' => true];
        case 'incorrecto':
          throw new \DomainException('El código no es correcto.');
        case 'caducado':
          throw new \DomainException('El código ha caducado. Pide uno nuevo.');
        case 'bloqueado':
          throw new \OverflowException('Demasiados intentos fallidos. Pide un código nuevo.');
        default:
          throw new \DomainException('Primero pide un código con «Enviar código».');
      }
    });
  }

  // ───────────────────────── Piezas ─────────────────────────

  /** Mismas reglas que el formulario de registro (validaciones.js). */
  private function nombre($v, string $campo): string {
    $v = trim((string) $v);
    $etiqueta = ucfirst($campo);
    if ($v === '')                                              throw new \InvalidArgumentException("El $campo es obligatorio.");
    if (mb_strlen($v) < 2)                                      throw new \InvalidArgumentException("$etiqueta: mínimo 2 caracteres.");
    if (mb_strlen($v) > 50)                                     throw new \InvalidArgumentException("$etiqueta: máximo 50 caracteres.");
    if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ'\\-\\s]+$/u", $v)) throw new \InvalidArgumentException("El $campo solo puede contener letras.");
    if (preg_match('/\s{2,}/', $v))                             throw new \InvalidArgumentException("El $campo no puede contener espacios dobles.");
    return $v;
  }

  /**
   * Envoltorio común: sesión iniciada, método, CSRF, JSON y errores.
   * DomainException/InvalidArgumentException → el mensaje es para el usuario; OverflowException → 429 (demasiados intentos);
   * cualquier otra cosa → 500 con mensaje genérico (el detalle va al log, no a la pantalla).
   */
  private function accion(string $metodo, callable $fn): void {
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');

    $correo = (string) ($_SESSION['email'] ?? '');
    if ($correo === '') {
      $this->responder(401, ['ok' => false, 'mensaje' => 'Tu sesión ha caducado. Vuelve a iniciar sesión.']);
    }
    if ($_SERVER['REQUEST_METHOD'] !== $metodo) {
      header('Allow: ' . $metodo);
      $this->responder(405, ['ok' => false, 'mensaje' => 'Método no permitido.']);
    }
    if ($metodo === 'POST') {
      $enviado = (string) ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
      if ($enviado === '' || !hash_equals(self::tokenCsrf(), $enviado)) {
        $this->responder(403, ['ok' => false, 'mensaje' => 'La solicitud no es válida. Recarga la página e inténtalo de nuevo.']);
      }
    }

    try {
      $this->responder(200, ['ok' => true] + $fn($correo));
    } catch (\InvalidArgumentException | \DomainException $e) {
      $this->responder(422, ['ok' => false, 'mensaje' => $e->getMessage()]);
    } catch (\OverflowException $e) {
      $this->responder(429, ['ok' => false, 'mensaje' => $e->getMessage()]);
    } catch (\Throwable $e) {
      error_log('Configuración: ' . $e->getMessage());
      $this->responder(500, ['ok' => false, 'mensaje' => 'No hemos podido completar la operación. Inténtalo de nuevo en unos minutos.']);
    }
  }

  protected function responder(int $codigo, array $datos): void {
    http_response_code($codigo);
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit;
  }
}
