<?php

namespace App\controlador;

use App\modelo\adminUsuariosModelo;
use App\modelo\actividadModelo;

/**
 * Inicio de sesión con Google (OAuth 2.0 / OpenID Connect, flujo "código de autorización" con PKCE).
 *
 *   /google/inicio    → redirige a Google
 *   /google/callback  → Google vuelve aquí con un código; se canjea por los datos de la cuenta
 *
 * Reglas:
 *  - Solo se entra si el correo de Google está VERIFICADO por Google y ya existe una cuenta con ese correo.
 *  - Si no existe, se lleva al formulario de registro con nombre, apellido y correo ya rellenados
 *    (faltan teléfono, país, fecha de nacimiento y contraseña, que la tabla Usuarios exige).
 *
 * Configuración (index.php): GOOGLE_CLIENT_ID y GOOGLE_CLIENT_SECRET. La URI de redirección que hay que dar de alta
 * en Google Cloud es  SITE_URL . '/google/callback'  (o GOOGLE_REDIRECT_URI si la defines).
 */
class googleControlador {

  private const URL_AUTORIZAR = 'https://accounts.google.com/o/oauth2/v2/auth';
  private const URL_TOKEN     = 'https://oauth2.googleapis.com/token';

  // ───────────────────────── Paso 1: ir a Google ─────────────────────────

  public function inicio(): void {
    if (!$this->configurado()) {
      $this->fallo('El inicio de sesión con Google todavía no está configurado. Usa tu correo y contraseña.');
    }

    $estado   = bin2hex(random_bytes(16));
    $nonce    = bin2hex(random_bytes(16));
    $verifier = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');

    $_SESSION['google_oauth'] = ['state' => $estado, 'nonce' => $nonce, 'verifier' => $verifier, 'creado' => time()];

    $url = self::URL_AUTORIZAR . '?' . http_build_query([
      'client_id'             => GOOGLE_CLIENT_ID,
      'redirect_uri'          => $this->redirectUri(),
      'response_type'         => 'code',
      'scope'                 => 'openid email profile',
      'state'                 => $estado,
      'nonce'                 => $nonce,
      'code_challenge'        => rtrim(strtr(base64_encode(hash('sha256', $verifier, true)), '+/', '-_'), '='),
      'code_challenge_method' => 'S256',
      'prompt'                => 'select_account',
    ], '', '&', PHP_QUERY_RFC3986);

    $this->redirigir($url, true);
  }

  // ───────────────────────── Paso 2: vuelta de Google ─────────────────────────

  public function callback(): void {
    $guardado = $_SESSION['google_oauth'] ?? null;
    unset($_SESSION['google_oauth']);   // de un solo uso

    if (!$this->configurado()) {
      $this->fallo('El inicio de sesión con Google todavía no está configurado.');
    }

    // El usuario canceló o Google devolvió un error
    if (!empty($_GET['error'])) {
      $this->fallo('Has cancelado el inicio de sesión con Google. Puedes volver a intentarlo cuando quieras.');
    }

    $estado = (string) ($_GET['state'] ?? '');
    $codigo = (string) ($_GET['code'] ?? '');

    // state: evita que alguien nos haga completar un inicio de sesión que no empezó este navegador (CSRF)
    if (!$guardado || $codigo === '' || !hash_equals($guardado['state'], $estado) || time() - (int) $guardado['creado'] > 600) {
      $this->fallo('La solicitud de inicio de sesión con Google no es válida o ha caducado. Inténtalo de nuevo.');
    }

    try {
      $datos = $this->intercambiarCodigo($codigo, $guardado['verifier']);
    } catch (\Throwable $e) {
      error_log('Google OAuth: ' . $e->getMessage());
      $this->fallo('No hemos podido conectar con Google. Inténtalo de nuevo en unos minutos.');
    }

    $perfil = $this->validarIdToken($datos['id_token'] ?? '', $guardado['nonce']);
    if (!$perfil) {
      $this->fallo('No hemos podido verificar tu cuenta de Google. Inténtalo de nuevo.');
    }

    $usuario = $this->buscarUsuario($perfil['email']);

    if (!$usuario) {
      // Sin cuenta: se lleva al registro con lo que ya sabemos
      $_SESSION['google_registro'] = [
        'nombre'   => $perfil['nombre'],
        'apellido' => $perfil['apellido'],
        'email'    => $perfil['email'],
      ];
      $_SESSION['toast'] = [
        'tipo'    => 'success',
        'icono'   => 'fa-user-plus',
        'titulo'  => 'Aún no tienes cuenta',
        'mensaje' => 'No hay ninguna cuenta con ' . $perfil['email'] . '. Hemos rellenado tus datos de Google: completa el registro para crearla.',
      ];
      $this->redirigir(BASE_PATH . '/acciones?tipo=registro');
    }

    // Igual que el inicio de sesión normal: nuevo id de sesión contra session fixation
    session_regenerate_id(true);

    $_SESSION['email']    = $usuario['Correo'];
    $_SESSION['nombre']   = $usuario['Nombre'];
    $_SESSION['apellido'] = $usuario['Apellido'];
    $_SESSION['rol']      = $usuario['Rol'];

    $this->registrarActividad($usuario['Correo']);

    $_SESSION['toast'] = [
      'tipo'    => 'success',
      'icono'   => 'fa-circle-check',
      'titulo'  => 'Sesión iniciada',
      'mensaje' => 'Hola, ' . $usuario['Nombre'] . '. Has entrado con tu cuenta de Google (' . $usuario['Correo'] . '). Ya puedes ver tus servicios y la tienda.',
    ];

    $this->redirigir(BASE_PATH . '/recursos');
  }

  // ───────────────────────── Piezas (protegidas para poder probarlas) ─────────────────────────

  /** Canjea el código por los tokens. Lanza excepción si Google responde con error. */
  protected function intercambiarCodigo(string $codigo, string $verifier): array {
    $ch = curl_init(self::URL_TOKEN);
    curl_setopt_array($ch, [
      CURLOPT_POST           => true,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT        => 10,
      CURLOPT_POSTFIELDS     => http_build_query([
        'code'          => $codigo,
        'client_id'     => GOOGLE_CLIENT_ID,
        'client_secret' => GOOGLE_CLIENT_SECRET,
        'redirect_uri'  => $this->redirectUri(),
        'grant_type'    => 'authorization_code',
        'code_verifier' => $verifier,
      ]),
    ]);
    $cuerpo = curl_exec($ch);
    $http   = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err    = curl_error($ch);
    curl_close($ch);

    if ($cuerpo === false) {
      throw new \RuntimeException('curl: ' . $err);
    }
    $json = json_decode($cuerpo, true);
    if ($http !== 200 || !is_array($json) || empty($json['id_token'])) {
      throw new \RuntimeException('token endpoint HTTP ' . $http . ': ' . substr($cuerpo, 0, 200));
    }
    return $json;
  }

  /**
   * Comprueba los datos del id_token. Llega directamente de Google por TLS en el canje del código, así que no hace
   * falta verificar la firma (lo permite la especificación de OpenID Connect); sí hay que comprobar a quién va dirigido,
   * quién lo emitió, que no haya caducado, el nonce y que Google haya verificado el correo.
   * Devuelve ['email','nombre','apellido'] o null.
   */
  protected function validarIdToken(string $jwt, string $nonce): ?array {
    $partes = explode('.', $jwt);
    if (count($partes) !== 3) {
      return null;
    }
    $p = json_decode((string) base64_decode(strtr($partes[1], '-_', '+/')), true);
    if (!is_array($p)) {
      return null;
    }

    $audOk   = ($p['aud'] ?? null) === GOOGLE_CLIENT_ID;
    $issOk   = in_array($p['iss'] ?? '', ['https://accounts.google.com', 'accounts.google.com'], true);
    $expOk   = (int) ($p['exp'] ?? 0) > time();
    $nonceOk = isset($p['nonce']) && hash_equals($nonce, (string) $p['nonce']);
    $email   = strtolower(trim((string) ($p['email'] ?? '')));
    $verif   = ($p['email_verified'] ?? false) === true || ($p['email_verified'] ?? '') === 'true';

    if (!$audOk || !$issOk || !$expOk || !$nonceOk || !$verif || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return null;
    }

    return [
      'email'    => $email,
      'nombre'   => mb_substr(trim((string) ($p['given_name'] ?? '')), 0, 50),
      'apellido' => mb_substr(trim((string) ($p['family_name'] ?? '')), 0, 50),
    ];
  }

  protected function buscarUsuario(string $email) {
    return adminUsuariosModelo::porCorreo($email);
  }

  protected function registrarActividad(string $email): void {
    actividadModelo::insertUltimaVezInicioSesion($email);
    actividadModelo::updateUltimaVezInicioSesion($email);
  }

  protected function redirigir(string $url, bool $externa = false): void {
    header('Location: ' . $url);
    exit;
  }

  /** Aviso de error y vuelta al formulario de inicio de sesión. */
  private function fallo(string $mensaje): void {
    $_SESSION['toast'] = [
      'tipo'    => 'error',
      'icono'   => 'fa-circle-exclamation',
      'titulo'  => 'No se pudo iniciar sesión con Google',
      'mensaje' => $mensaje,
    ];
    $this->redirigir(BASE_PATH . '/acciones?tipo=inicio');
  }

  private function configurado(): bool {
    return defined('GOOGLE_CLIENT_ID') && defined('GOOGLE_CLIENT_SECRET')
      && GOOGLE_CLIENT_ID !== '' && GOOGLE_CLIENT_SECRET !== ''
      && strpos(GOOGLE_CLIENT_ID, 'TU_') !== 0 && strpos(GOOGLE_CLIENT_SECRET, 'TU_') !== 0;
  }

  protected function redirectUri(): string {
    if (defined('GOOGLE_REDIRECT_URI') && GOOGLE_REDIRECT_URI !== '') {
      return GOOGLE_REDIRECT_URI;
    }
    return rtrim(defined('SITE_URL') ? SITE_URL : '', '/') . '/google/callback';
  }
}
