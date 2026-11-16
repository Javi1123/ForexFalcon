<?php

namespace App\controlador;

use App\core\Contrasena;
use App\modelo\adminUsuariosModelo;
use App\modelo\correoModelo;
use App\modelo\restablecerModelo;

/**
 * "He olvidado mi contraseña".
 *
 *   /recuperar                → el usuario escribe su correo; si hay cuenta, se le envía un enlace de un solo uso
 *   /restablecer?token=...    → el enlace del correo; guarda el token en la sesión y limpia la dirección
 *   /restablecer              → formulario de contraseña nueva (solo se abre si la sesión trae un token válido)
 *
 * La única forma de llegar al formulario es con el enlace que se manda por correo: el token no se muestra en
 * ninguna pantalla, caduca, solo vale una vez y en la base de datos solo se guarda su hash.
 */
class restablecerControlador {

  // ───────────────────────── Paso 1: pedir el enlace ─────────────────────────

  public function solicitar(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $email = strtolower(trim((string) ($_POST['email_recuperar'] ?? '')));

      if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
        $this->aviso('error', 'fa-circle-exclamation', 'Correo no válido', 'Escribe un correo electrónico válido.');
        $this->ir('/recuperar');
      }

      // Respuesta idéntica exista o no la cuenta (así no se puede averiguar qué correos están registrados)
      $this->aviso('success', 'fa-paper-plane', 'Revisa tu correo',
        'Si existe una cuenta con ' . $email . ', te hemos enviado un enlace para restablecer la contraseña. Caduca en ' . restablecerModelo::MINUTOS_VALIDEZ . ' minutos.');

      // Se responde al navegador YA y el trabajo (consulta + envío del correo) sigue después: si no, la respuesta
      // tardaría más cuando la cuenta existe, y eso también delataría qué correos están registrados.
      $this->cerrarRespuesta('/acciones?tipo=inicio');
      $this->procesarSolicitud($email);
      exit;
    }

    $this->vista('solicitar');
  }

  // ───────────────────────── Paso 2: abrir el enlace y elegir contraseña ─────────────────────────

  public function restablecer(): void {
    // Llegada desde el correo: el token viene en la URL. Se valida, se guarda en la sesión y se redirige a una
    // dirección limpia, para que el token no se quede en la barra del navegador, en el historial ni en cabeceras Referer.
    if (isset($_GET['token'])) {
      $token = (string) $_GET['token'];
      if (restablecerModelo::correoDeToken($token) !== null) {
        $_SESSION['restablecer_token'] = $token;
        $_SESSION['restablecer_csrf']  = bin2hex(random_bytes(16));
      } else {
        unset($_SESSION['restablecer_token'], $_SESSION['restablecer_csrf']);
      }
      $this->ir('/restablecer');
    }

    $token  = (string) ($_SESSION['restablecer_token'] ?? '');
    $correo = $token !== '' ? restablecerModelo::correoDeToken($token) : null;

    if ($correo === null) {
      unset($_SESSION['restablecer_token'], $_SESSION['restablecer_csrf']);
      $this->vista('invalido');
      return;
    }

    $errorReset = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $csrfOk = hash_equals((string) ($_SESSION['restablecer_csrf'] ?? ''), (string) ($_POST['csrf'] ?? ''));
      $nueva  = (string) ($_POST['contraseña_nueva'] ?? '');
      $repite = (string) ($_POST['confirmar_contraseña_nueva'] ?? '');

      if (!$csrfOk) {
        $errorReset = 'La solicitud no es válida. Recarga la página e inténtalo de nuevo.';
      } elseif (($p = Contrasena::errorDePolitica($nueva)) !== null) {
        $errorReset = $p;
      } elseif ($nueva !== $repite) {
        $errorReset = 'Las dos contraseñas no coinciden.';
      } else {
        try {
          $hecho = restablecerModelo::aplicar($token, $nueva);
        } catch (\Throwable $e) {
          error_log('Restablecer contraseña: ' . $e->getMessage());
          $hecho = false;
          $errorReset = 'No hemos podido guardar la contraseña. Inténtalo de nuevo en unos minutos.';
        }

        if ($hecho) {
          unset($_SESSION['restablecer_token'], $_SESSION['restablecer_csrf']);

          $u = adminUsuariosModelo::porCorreo($correo);
          try {
            correoModelo::correo_contrasena_cambiada($correo, $u['Nombre'] ?? '');
          } catch (\Throwable $e) {
            error_log('Aviso de cambio de contraseña: ' . $e->getMessage());
          }

          $this->aviso('success', 'fa-key', 'Contraseña cambiada', 'Ya puedes iniciar sesión con tu contraseña nueva.');
          $this->ir('/acciones?tipo=inicio');
        }

        if ($errorReset === '') {
          // El enlace se gastó o caducó mientras tanto
          unset($_SESSION['restablecer_token'], $_SESSION['restablecer_csrf']);
          $this->vista('invalido');
          return;
        }
      }
    }

    $csrf = $_SESSION['restablecer_csrf'];
    $this->vista('nueva', ['errorReset' => $errorReset, 'csrf' => $csrf]);
  }

  // ───────────────────────── Piezas ─────────────────────────

  private function procesarSolicitud(string $email): void {
    try {
      $u = adminUsuariosModelo::porCorreo($email);
      if (!$u) {
        return;
      }
      $token = restablecerModelo::crear($u['Correo'], $_SERVER['REMOTE_ADDR'] ?? null);
      if ($token === null) {
        return;   // demasiadas solicitudes seguidas: no se manda otro correo
      }

      $base   = defined('SITE_URL') ? rtrim(SITE_URL, '/') : (BASE_PATH);
      $enlace = $base . '/restablecer?token=' . $token;

      $enviado = correoModelo::correo_restablecer($u['Correo'], $u['Nombre'], $enlace, restablecerModelo::MINUTOS_VALIDEZ);

      // Solo en desarrollo (localhost suele no tener servidor de correo): se deja el enlace en el log de PHP
      if (!$enviado && strpos((string) (defined('SITE_URL') ? SITE_URL : ''), '://localhost') !== false) {
        error_log('[DESARROLLO] mail() no pudo enviar. Enlace de restablecimiento para ' . $u['Correo'] . ': ' . $enlace);
      }
    } catch (\Throwable $e) {
      error_log('Solicitud de restablecimiento: ' . $e->getMessage());
    }
  }

  /** Envía la redirección al navegador y sigue trabajando en segundo plano. */
  private function cerrarRespuesta(string $ruta): void {
    ignore_user_abort(true);
    header('Location: ' . BASE_PATH . $ruta);
    http_response_code(302);
    header('Content-Length: 0');
    header('Connection: close');
    session_write_close();   // el aviso ya está guardado en la sesión
    if (function_exists('fastcgi_finish_request')) {
      fastcgi_finish_request();
    } else {
      while (ob_get_level() > 0) {
        ob_end_flush();
      }
      flush();
    }
  }

  private function aviso(string $tipo, string $icono, string $titulo, string $mensaje): void {
    $_SESSION['toast'] = ['tipo' => $tipo, 'icono' => $icono, 'titulo' => $titulo, 'mensaje' => $mensaje];
  }

  private function ir(string $ruta): void {
    header('Location: ' . BASE_PATH . $ruta);
    exit;
  }

  private function vista(string $modo, array $datos = []): void {
    header('Referrer-Policy: no-referrer');
    header('Cache-Control: no-store');
    extract($datos);
    require __DIR__ . '/../vista/restablecer_vista.php';
  }
}
