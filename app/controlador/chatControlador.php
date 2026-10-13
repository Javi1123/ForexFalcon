<?php

namespace App\controlador;

use App\core\DB;
use App\core\PusherClient;
use App\modelo\chatModelo;
use App\modelo\mensajeChatModelo;

/**
 * Chat de mensajería rápida (burbuja de /recursos).
 * Todas las acciones responden JSON; las llama chat.js con fetch().
 *
 * Reglas:
 *  - Hace falta sesión iniciada.
 *  - Todo lo que escribe (POST) exige el token CSRF en la cabecera X-CSRF-Token.
 *  - Un usuario solo ve/escribe en SUS chats; un administrador solo en los que ÉL ha cogido.
 *  - Lo importante se guarda en BD primero; Pusher solo reparte el aviso en directo.
 */
class chatControlador {

  private const MAX_MENSAJE = 1000;

  public function __construct() {
    // Los mensajes llevan acentos y emojis: fuerza utf8mb4 SOLO en las peticiones del chat,
    // sin tocar app/core/DB.php (así el resto de la web se comporta exactamente igual que antes).
    DB::getInstance()->exec("SET NAMES utf8mb4");
  }

  /** Token CSRF de la sesión; lo imprime chat_burbuja.php para que chat.js lo envíe. */
  public static function tokenCsrf(): string {
    if (empty($_SESSION['chat_csrf'])) {
      $_SESSION['chat_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['chat_csrf'];
  }

  /** Rol de la sesión. Si la sesión es anterior al chat (sin 'rol'), lo busca en la BD. */
  public static function rolActual(): string {
    if (!isset($_SESSION['rol'])) {
      $_SESSION['rol'] = chatModelo::rolDeUsuario($_SESSION['email']) ?? 'Usuario';
    }
    return $_SESSION['rol'];
  }

  public static function esAdmin(): bool {
    return in_array(self::rolActual(), ['Administrador', 'Superusuario'], true);
  }

  // ───────────────────────── Endpoints ─────────────────────────

  /** GET: ¿tiene el usuario un chat abierto? (para retomarlo al recargar la página) */
  public function estado(): void {
    $correo = $this->exigirSesion();
    if (self::esAdmin()) {
      $this->responder(['chat' => null]);
    }

    $chat = chatModelo::abiertoDeUsuario($correo);
    $this->responder(['chat' => $chat ? ['id_chat' => (int) $chat['Id_chat'], 'estado' => $chat['Estado']] : null]);
  }

  /** POST: el usuario abre un chat con soporte (o recupera el que ya tenía abierto). */
  public function crear(): void {
    $correo = $this->exigirSesion();
    $this->exigirPost();
    if (self::esAdmin()) {
      $this->responder(['error' => 'Un administrador no puede abrir un chat de soporte.'], 400);
    }

    $existente = chatModelo::abiertoDeUsuario($correo);
    if ($existente) {
      $this->responder(['id_chat' => (int) $existente['Id_chat'], 'estado' => $existente['Estado']]);
    }

    $idChat = chatModelo::crear($correo);

    PusherClient::get()->trigger('private-admins-lobby', 'nuevo-chat', [
      'id_chat'        => $idChat,
      'correo_usuario' => $correo,
      'nombre'         => $this->nombreCompleto(),
    ]);

    $this->responder(['id_chat' => $idChat, 'estado' => 'esperando']);
  }

  /** GET (admin): chats en espera + los que este admin ya está atendiendo. */
  public function pendientes(): void {
    $correo = $this->exigirSesion();
    $this->exigirAdmin();

    $this->responder([
      'pendientes' => array_map([$this, 'filaLista'], chatModelo::pendientes()),
      'activos'    => array_map([$this, 'filaLista'], chatModelo::activosDeAdmin($correo)),
    ]);
  }

  /** POST (admin): coge un chat en espera. */
  public function asignar(): void {
    $correo = $this->exigirSesion();
    $this->exigirPost();
    $this->exigirAdmin();

    $idChat = (int) ($_POST['id_chat'] ?? 0);
    if (!chatModelo::asignar($idChat, $correo)) {
      $this->responder(['ok' => false, 'error' => 'Este chat ya lo ha cogido otro administrador o ya no existe.'], 409);
    }

    $chat = chatModelo::porId($idChat);
    $pusher = PusherClient::get();
    // Avisa al usuario (su canal) y a los demás admins (para que lo quiten de "en espera")
    $pusher->trigger("presence-chat-$idChat", 'chat-asignado', ['id_chat' => $idChat]);
    $pusher->trigger('private-admins-lobby', 'chat-tomado', ['id_chat' => $idChat]);

    $this->responder(['ok' => true, 'chat' => $this->filaLista($chat)]);
  }

  /** POST: enviar un mensaje. */
  public function mensaje(): void {
    $correo = $this->exigirSesion();
    $this->exigirPost();

    $idChat  = (int) ($_POST['id_chat'] ?? 0);
    $mensaje = trim((string) ($_POST['mensaje'] ?? ''));

    if ($idChat <= 0 || $mensaje === '') {
      $this->responder(['error' => 'Escribe un mensaje.'], 400);
    }
    if (mb_strlen($mensaje) > self::MAX_MENSAJE) {
      $this->responder(['error' => 'El mensaje es demasiado largo (máximo ' . self::MAX_MENSAJE . ' caracteres).'], 400);
    }

    $chat = $this->cargarChatAutorizado($idChat, $correo);
    if ($chat['Estado'] === 'cerrado') {
      $this->responder(['error' => 'Este chat ya está cerrado.'], 409);
    }
    if (self::esAdmin() && $chat['Estado'] !== 'activo') {
      $this->responder(['error' => 'Coge el chat antes de escribir.'], 409);
    }

    $rol = self::esAdmin() ? 'Administrador' : 'Usuario';

    // Se guarda TAL CUAL; el navegador lo pinta con textContent, así que no puede ejecutar HTML.
    $msg = mensajeChatModelo::crear($idChat, $correo, $rol, $mensaje);

    // El emisor ya lo pinta con esta respuesta → se le excluye del evento para no duplicarlo.
    PusherClient::get()->trigger("presence-chat-$idChat", 'nuevo-mensaje', $msg, $_POST['socket_id'] ?? null);

    $this->responder(['ok' => true, 'mensaje' => $msg]);
  }

  /** GET: historial de un chat. */
  public function historial(): void {
    $correo = $this->exigirSesion();

    $idChat = (int) ($_GET['id_chat'] ?? 0);
    $chat   = $this->cargarChatAutorizado($idChat, $correo);

    $this->responder([
      'estado'   => $chat['Estado'],
      'mensajes' => mensajeChatModelo::historial($idChat),
    ]);
  }

  /** POST: cierra un chat (lo puede cerrar el usuario o el admin que lo atiende). */
  public function cerrar(): void {
    $correo = $this->exigirSesion();
    $this->exigirPost();

    $idChat = (int) ($_POST['id_chat'] ?? 0);
    $this->cargarChatAutorizado($idChat, $correo);

    chatModelo::cerrar($idChat);

    $pusher = PusherClient::get();
    $pusher->trigger("presence-chat-$idChat", 'chat-cerrado', ['id_chat' => $idChat]);
    $pusher->trigger('private-admins-lobby', 'chat-tomado', ['id_chat' => $idChat]); // refresca la lista de admins

    $this->responder(['ok' => true]);
  }

  /** POST (lo llama pusher-js solo): firma la suscripción a un canal. */
  public function pusher_auth(): void {
    $correo = $this->exigirSesion();
    $this->exigirPost(false);

    $socketId = (string) ($_POST['socket_id'] ?? '');
    $canal    = (string) ($_POST['channel_name'] ?? '');

    if (!preg_match('/^\d+\.\d+$/', $socketId) || $canal === '' || !PusherClient::configurado()) {
      $this->responder(['error' => 'Petición inválida.'], 400);
    }

    $pusher = PusherClient::get();

    // Lobby donde los admins reciben "hay un chat nuevo"
    if ($canal === 'private-admins-lobby') {
      if (!self::esAdmin()) {
        $this->responder(['error' => 'Solo administradores.'], 403);
      }
      $this->responder($pusher->autorizar($socketId, $canal));
    }

    // Canal de una conversación: presence-chat-{id}
    if (preg_match('/^presence-chat-(\d+)$/', $canal, $m)) {
      $this->cargarChatAutorizado((int) $m[1], $correo);   // corta con 403/404 si no es suyo

      $this->responder($pusher->autorizar($socketId, $canal, [
        'user_id'   => $correo,
        'user_info' => ['rol' => self::esAdmin() ? 'Administrador' : 'Usuario', 'nombre' => $_SESSION['nombre'] ?? ''],
      ]));
    }

    $this->responder(['error' => 'Canal no permitido.'], 403);
  }

  // ───────────────────────── Ayudas ─────────────────────────

  /** Devuelve el chat si el usuario puede acceder; si no, responde 404/403 y termina. */
  private function cargarChatAutorizado(int $idChat, string $correo): array {
    $chat = $idChat > 0 ? chatModelo::porId($idChat) : false;
    if (!$chat) {
      $this->responder(['error' => 'Chat no encontrado.'], 404);
    }

    $esDueno      = $chat['Correo_usuario'] === $correo;
    $loAtiendeYo  = self::esAdmin() && $chat['Correo_admin'] === $correo;
    if (!$esDueno && !$loAtiendeYo) {
      $this->responder(['error' => 'No perteneces a este chat.'], 403);
    }
    return $chat;
  }

  private function filaLista(array $c): array {
    return [
      'id_chat' => (int) $c['Id_chat'],
      'nombre'  => trim(($c['Nombre'] ?? '') . ' ' . ($c['Apellido'] ?? '')),
      'correo'  => $c['Correo_usuario'],
      'hora'    => $c['Hora'] ?? '',
    ];
  }

  private function nombreCompleto(): string {
    return trim(($_SESSION['nombre'] ?? '') . ' ' . ($_SESSION['apellido'] ?? ''));
  }

  private function exigirSesion(): string {
    if (empty($_SESSION['email'])) {
      $this->responder(['error' => 'Tu sesión ha caducado. Vuelve a iniciar sesión.'], 401);
    }
    return $_SESSION['email'];
  }

  private function exigirAdmin(): void {
    if (!self::esAdmin()) {
      $this->responder(['error' => 'Solo administradores.'], 403);
    }
  }

  private function exigirPost(bool $conCsrf = true): void {
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
      $this->responder(['error' => 'Método no permitido.'], 405);
    }
    if ($conCsrf) {
      $esperado = $_SESSION['chat_csrf'] ?? '';
      $recibido = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
      if ($esperado === '' || !hash_equals($esperado, $recibido)) {
        $this->responder(['error' => 'Petición no válida. Recarga la página.'], 403);
      }
    }
  }

  private function responder(array $datos, int $codigo = 200): void {
    http_response_code($codigo);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit;
  }
}
