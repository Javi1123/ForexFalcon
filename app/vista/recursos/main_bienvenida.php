<?php
/* Página de bienvenida de /recursos (solo usuarios; los administradores van a su panel). */

$nombreBienvenida = trim($_SESSION['nombre'] ?? '');

// Enlace de WhatsApp: misma lógica que la burbuja de mensajería (chat_burbuja.php)
$waNumeroB = defined('WHATSAPP_NUMERO') ? ltrim(preg_replace('/\D/', '', WHATSAPP_NUMERO), '0') : '';
$waUrlB = '';
if (defined('WHATSAPP_NUMERO') && strpos(WHATSAPP_NUMERO, 'TU_') !== 0
    && strlen($waNumeroB) >= 8 && strlen($waNumeroB) <= 15) {
  $waNombreB = trim(($_SESSION['nombre'] ?? '') . ' ' . ($_SESSION['apellido'] ?? ''));
  $waTextoB  = (defined('WHATSAPP_MENSAJE') ? WHATSAPP_MENSAJE : 'Hola, necesito ayuda con ForexFalcon.')
             . ($waNombreB !== '' ? "\n\nMi nombre: {$waNombreB}" : '')
             . "\nMi correo: " . $_SESSION['email'];
  $waUrlB = 'https://wa.me/' . $waNumeroB . '?text=' . rawurlencode($waTextoB);
}

$funciones = [
  ['fa-solid fa-shop', 'Tienda',
   'Consulta todos los servicios disponibles, lee su descripción completa y contrata el que te interese.'],
  ['fa-solid fa-circle-play', 'Vídeos de formación',
   'Cada servicio de vídeo reúne sus lecciones en un reproductor propio, con la lista de vídeos a un lado.'],
  ['fa-solid fa-bars-staggered', 'Mis Servicios',
   'En la barra lateral aparecen los servicios que tienes contratados, para entrar a ellos con un solo clic.'],
  ['fa-solid fa-comments', 'Chat con el equipo',
   'La burbuja de abajo a la derecha te conecta en directo con un administrador para resolver tus dudas.'],
  ['fa-brands fa-whatsapp', 'WhatsApp',
   'Desde esa misma burbuja puedes abrir una conversación de WhatsApp con nosotros en una pestaña nueva.'],
  ['fa-solid fa-gear', 'Tu cuenta',
   'En el menú de tu perfil (arriba a la derecha) puedes abrir la configuración o cerrar la sesión.'],
];

$pasosCompra = [
  ['Entra en la tienda', 'Pulsa «Tienda» en el menú lateral o en el botón de esta página.'],
  ['Elige tu servicio', 'Pulsa el botón de información de cada tarjeta para ver qué incluye antes de decidir.'],
  ['Pulsa «Proceder con el pago»', 'Te llevaremos a la página de pago segura de Stripe, donde también puedes introducir un código promocional si tienes uno.'],
  ['Empieza a disfrutarlo', 'Al terminar el pago, el servicio aparece en «Mis Servicios» en la barra lateral.'],
];
?>
<main class="app-main bienvenida">
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <h3 class="mb-0 bienvenida-titulo">
            ¡Bienvenido<?= $nombreBienvenida !== '' ? ', ' . htmlspecialchars($nombreBienvenida) : '' ?>!
          </h3>
          <p class="bienvenida-sub">Esta es tu zona privada de ForexFalcon. Aquí tienes un resumen de lo que puedes hacer y de cómo contactar con nosotros.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="app-content">
    <div class="container-fluid">

      <!-- Qué puedes hacer en esta zona -->
      <h4 class="bienvenida-seccion" data-reveal>¿Qué puedes hacer aquí?</h4>
      <div class="bienvenida-grid">
        <?php foreach ($funciones as $i => $f): ?>
          <div class="bienvenida-card" data-reveal style="--i: <?= $i ?>">
            <div class="bienvenida-card__icono"><i class="<?= $f[0] ?>"></i></div>
            <h5><?= htmlspecialchars($f[1]) ?></h5>
            <p><?= htmlspecialchars($f[2]) ?></p>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="bienvenida-paneles">

        <!-- Cómo contactar -->
        <div class="bienvenida-col">
          <div class="bienvenida-panel" data-reveal style="--i: 0">
            <h4 class="bienvenida-seccion"><i class="fa-solid fa-headset"></i> Cómo contactar con nosotros</h4>
            <p>Estamos para ayudarte. Tienes dos vías, y las dos salen de la <strong>burbuja de mensajería</strong> que encontrarás en la esquina inferior derecha de cualquier página de esta zona:</p>
            <ul class="bienvenida-lista">
              <li><strong>Chat con un administrador:</strong> abre la burbuja, elige «Hablar con un administrador» y escribe tu mensaje. Te atenderemos en directo.</li>
              <li><strong>WhatsApp:</strong> elige la opción de WhatsApp y se abrirá la conversación con nosotros en una pestaña nueva, sin que pierdas esta página.</li>
            </ul>
            <div class="bienvenida-botones">
              <button type="button" class="btnRegistrarse" data-abrir-chat>
                <i class="fa-solid fa-comments"></i> Abrir el chat
              </button>
              <?php if ($waUrlB): ?>
                <a class="btnRegistrarse bienvenida-btn-wa" href="<?= htmlspecialchars($waUrlB) ?>" target="_blank" rel="noopener noreferrer">
                  <i class="fa-brands fa-whatsapp"></i> Abrir WhatsApp
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- Cómo comprar -->
        <div class="bienvenida-col">
          <div class="bienvenida-panel" data-reveal style="--i: 1">
            <h4 class="bienvenida-seccion"><i class="fa-solid fa-cart-shopping"></i> Cómo comprar un producto</h4>
            <ol class="bienvenida-pasos">
              <?php foreach ($pasosCompra as $k => $p): ?>
                <li style="--k: <?= $k ?>">
                  <strong><?= htmlspecialchars($p[0]) ?></strong>
                  <span><?= htmlspecialchars($p[1]) ?></span>
                </li>
              <?php endforeach; ?>
            </ol>
            <p class="bienvenida-nota">¿Dudas antes de comprar? Escríbenos por el chat o por WhatsApp y te ayudamos a elegir.</p>
            <div class="bienvenida-botones">
              <a class="btnRegistrarse" href="<?= BASE_PATH . '/tienda' ?>">
                <i class="fa-solid fa-shop"></i> Ir a la tienda
              </a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</main>
<script src="<?= LINKS_PATH . '/js/code_js_jquerys/bienvenida.js?v=' . (@filemtime(__DIR__ . '/../../../public/js/code_js_jquerys/bienvenida.js') ?: 1) ?>" defer></script>
