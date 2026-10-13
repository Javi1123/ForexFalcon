<?php
/**
 * Burbuja de mensajería rápida — abajo a la derecha de /recursos.
 * Se incluye desde recursos_vista.php. Es la misma burbuja para todos:
 *   - Usuario:       "Hablar con un administrador" + WhatsApp (próximamente)
 *   - Administrador: bandeja de chats en espera / en curso + WhatsApp (próximamente)
 */

use App\controlador\chatControlador;
use App\core\PusherClient;

$chatEsAdmin = chatControlador::esAdmin();

$chatConfig = [
  'base'        => BASE_PATH,
  'csrf'        => chatControlador::tokenCsrf(),
  'correo'      => $_SESSION['email'],
  'nombre'      => $_SESSION['nombre'] ?? '',
  'esAdmin'     => $chatEsAdmin,
  'rol'         => $chatEsAdmin ? 'Administrador' : 'Usuario',
  'configurado' => PusherClient::configurado(),
  'pusherKey'   => PusherClient::configurado() ? PUSHER_KEY : '',
  'cluster'     => PUSHER_CLUSTER,
];
?>

<!-- ═════════ CHAT: burbuja de mensajería rápida ═════════ -->
<div class="ff-chat" id="ffChat">

  <button type="button" class="ff-chat__launcher" id="ffChatLauncher"
          aria-label="Abrir mensajería rápida" aria-expanded="false" aria-controls="ffChatPanel">
    <i class="fa-solid fa-comments ff-chat__ico-abrir" aria-hidden="true"></i>
    <i class="fa-solid fa-xmark ff-chat__ico-cerrar" aria-hidden="true"></i>
    <span class="ff-chat__badge" id="ffChatBadge" hidden>0</span>
  </button>

  <section class="ff-chat__panel" id="ffChatPanel" role="dialog" aria-label="Mensajería rápida" hidden>

    <header class="ff-chat__header">
      <button type="button" class="ff-chat__iconbtn" id="ffChatVolver" aria-label="Volver" hidden>
        <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
      </button>
      <div class="ff-chat__title">
        <strong id="ffChatTitulo">Mensajería rápida</strong>
        <span id="ffChatSubtitulo">¿En qué podemos ayudarte?</span>
      </div>
      <button type="button" class="ff-chat__iconbtn" id="ffChatMinimizar" aria-label="Cerrar mensajería">
        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
      </button>
    </header>

    <div class="ff-chat__body">

      <div class="ff-chat__error" id="ffChatError" role="alert" hidden></div>

      <!-- Vista 1: menú de opciones -->
      <div class="ff-chat__view" data-vista="menu">
        <button type="button" class="ff-chat__option" id="ffOpcionAdmin">
          <span class="ff-chat__option-ico"><i class="fa-solid fa-headset" aria-hidden="true"></i></span>
          <span class="ff-chat__option-txt">
            <?php if ($chatEsAdmin): ?>
              <strong>Chats de soporte</strong>
              <small>Atiende a los usuarios que te escriben</small>
            <?php else: ?>
              <strong>Hablar con un administrador</strong>
              <small>Te respondemos por aquí, en directo</small>
            <?php endif; ?>
          </span>
          <span class="ff-chat__pill ff-chat__pill--alerta" id="ffOpcionAdminPill" hidden>0</span>
        </button>

        <!-- WhatsApp: se conectará más adelante -->
        <button type="button" class="ff-chat__option ff-chat__option--off" id="ffOpcionWhatsapp" aria-disabled="true">
          <span class="ff-chat__option-ico ff-chat__option-ico--wa"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i></span>
          <span class="ff-chat__option-txt">
            <strong>WhatsApp</strong>
            <small>Escríbenos desde tu móvil</small>
          </span>
          <span class="ff-chat__pill">Próximamente</span>
        </button>
      </div>

      <!-- Vista 2 (solo administradores): lista de chats -->
      <div class="ff-chat__view" data-vista="lista" hidden>
        <h4 class="ff-chat__section">En espera <span id="ffCuentaPendientes">0</span></h4>
        <div class="ff-chat__list" id="ffListaPendientes"></div>
        <h4 class="ff-chat__section">Mis conversaciones <span id="ffCuentaActivos">0</span></h4>
        <div class="ff-chat__list" id="ffListaActivos"></div>
      </div>

      <!-- Vista 3: conversación -->
      <div class="ff-chat__view ff-chat__view--chat" data-vista="chat" hidden>
        <div class="ff-chat__status" id="ffChatEstado" data-tipo="espera">
          <span class="ff-chat__dot" aria-hidden="true"></span>
          <span id="ffChatEstadoTxt">Conectando…</span>
        </div>

        <div class="ff-chat__messages" id="ffChatMensajes" role="log" aria-live="polite" aria-label="Mensajes"></div>
        <div class="ff-chat__typing" id="ffChatEscribiendo" aria-live="polite"></div>

        <div class="ff-chat__closed" id="ffChatCerrado" hidden>
          <span>Esta conversación ha terminado.</span>
          <button type="button" class="ff-chat__btn" id="ffChatNueva"></button>
        </div>

        <form class="ff-chat__form" id="ffChatForm" autocomplete="off">
          <input type="text" id="ffChatInput" maxlength="1000" placeholder="Escribe tu mensaje…"
                 aria-label="Escribe tu mensaje" disabled>
          <button type="submit" id="ffChatEnviar" aria-label="Enviar mensaje" disabled>
            <i class="fa-solid fa-paper-plane" aria-hidden="true"></i>
          </button>
        </form>

        <button type="button" class="ff-chat__finish" id="ffChatFinalizar">
          <?= $chatEsAdmin ? 'Cerrar este chat' : 'Finalizar conversación' ?>
        </button>
      </div>

    </div>
  </section>
</div>

<script>
  window.FF_CHAT = <?= json_encode($chatConfig, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
</script>
<script src="<?= LINKS_PATH . "/js/code_js_jquerys/chat.js" ?>" defer></script>
