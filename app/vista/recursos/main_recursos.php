<?php
/* Tienda (/tienda). Variables: $servicios (Stripe), $tiposServicio y $contratados (BD, opcionales). */

$tiposServicio = $tiposServicio ?? [];
$contratados   = $contratados ?? [];

$tipoInfo = [
  'video'    => ['Vídeo',    'fa-solid fa-circle-play', 'Lecciones en vídeo'],
  'imagenes' => ['Imágenes', 'fa-solid fa-images',      'Galería de imágenes'],
  'chat'     => ['Chat',     'fa-solid fa-comments',    'Atención por chat'],
];

$totalServicios = count($servicios);
$totalContratados = 0;
foreach ($servicios as $s) {
  if (isset($contratados[strtoupper($s['id_servicio'])])) $totalContratados++;
}

// Tipos que realmente aparecen (para mostrar solo los filtros útiles)
$tiposPresentes = [];
foreach ($servicios as $s) {
  $t = $tiposServicio[strtoupper($s['id_servicio'])] ?? '';
  if (isset($tipoInfo[$t])) $tiposPresentes[$t] = true;
}

$etiquetaPeriodo = function (string $periodo): string {
  $mapa = ['pago único' => 'Pago único', '/mes' => 'Suscripción mensual', '/semana' => 'Suscripción semanal',
           '/año' => 'Suscripción anual', '/día' => 'Suscripción diaria'];
  return $mapa[$periodo] ?? 'Suscripción';
};
?>
<main class="app-main tienda">

  <div class="app-content-header">
    <div class="container-fluid">
      <!-- Cabecera de la tienda -->
      <section class="tienda-hero" data-reveal>
        <div class="tienda-hero__texto">
          <h3 class="tienda-hero__titulo"><i class="fa-solid fa-shop"></i> Tienda</h3>
          <p>Elige el servicio que mejor encaje contigo. Cada tarjeta te cuenta qué incluye, cuánto cuesta y cómo se paga; si tienes dudas, te ayudamos por el chat.</p>
        </div>
        <ul class="tienda-hero__datos">
          <li><strong><?= $totalServicios ?></strong><span>servicio<?= $totalServicios === 1 ? '' : 's' ?> disponible<?= $totalServicios === 1 ? '' : 's' ?></span></li>
          <li><strong><?= $totalContratados ?></strong><span>contratado<?= $totalContratados === 1 ? '' : 's' ?> por ti</span></li>
        </ul>
      </section>
    </div>
  </div>

  <div class="app-content">
    <div class="container-fluid">

      <?php if ($totalServicios === 0): ?>
        <div class="tienda-vacia" data-reveal>
          <i class="fa-regular fa-clock"></i>
          <h4>Estamos preparando nuevos servicios</h4>
          <p>Ahora mismo no hay nada a la venta. Vuelve pronto o escríbenos por el chat y te avisamos.</p>
          <button type="button" class="btnRegistrarse" data-abrir-chat><i class="fa-solid fa-comments"></i> Hablar con el equipo</button>
        </div>
      <?php else: ?>

      <?php if (count($tiposPresentes) > 0): ?>
      <!-- Filtros por tipo de servicio -->
      <div class="tienda-filtros" role="tablist" aria-label="Filtrar servicios por tipo" data-reveal style="--i: 1">
        <button type="button" class="tienda-chip activo" data-filtro="todos">
          <i class="fa-solid fa-border-all"></i> Todos <span class="tienda-chip__n"><?= $totalServicios ?></span>
        </button>
        <?php foreach ($tipoInfo as $clave => $info): if (empty($tiposPresentes[$clave])) continue; ?>
          <button type="button" class="tienda-chip" data-filtro="<?= $clave ?>">
            <i class="<?= $info[1] ?>"></i> <?= $info[0] ?>
          </button>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <div class="tienda-grid" id="tiendaGrid">
        <?php foreach ($servicios as $i => $s):
          $codigo     = strtoupper($s['id_servicio']);
          $tipo       = $tiposServicio[$codigo] ?? '';
          $info       = $tipoInfo[$tipo] ?? null;
          $contratado = isset($contratados[$codigo]);
          $urlPago    = BASE_PATH . '/pago?mode=' . rawurlencode($s['mode']) . '&id_servicio=' . rawurlencode($s['id_servicio']);
          $urlServ    = BASE_PATH . '/servicio?id=' . rawurlencode($s['id_servicio']);
          $caract     = $s['caracteristicas'] ?? [];
        ?>
          <article class="tienda-card<?= $contratado ? ' es-contratado' : '' ?>" data-reveal data-tipo="<?= htmlspecialchars($tipo) ?>" style="--i: <?= $i % 6 ?>">

            <div class="tienda-card__cabeza">
              <div class="tienda-card__icono"><i class="<?= htmlspecialchars($s['icono']) ?>"></i></div>
              <div class="tienda-card__etiquetas">
                <?php if ($info): ?><span class="tienda-tag"><i class="<?= $info[1] ?>"></i> <?= $info[0] ?></span><?php endif; ?>
                <?php if ($contratado): ?><span class="tienda-tag tienda-tag--ok"><i class="fa-solid fa-circle-check"></i> Ya contratado</span><?php endif; ?>
              </div>
            </div>

            <h3><?= htmlspecialchars($s['nombre']) ?></h3>
            <p class="tienda-card__desc"><?= htmlspecialchars($s['descripcion_corta']) ?></p>

            <?php if ($caract): ?>
              <ul class="tienda-card__lista">
                <?php foreach (array_slice($caract, 0, 3) as $c): ?>
                  <li><i class="fa-solid fa-check"></i> <?= htmlspecialchars($c) ?></li>
                <?php endforeach; ?>
                <?php if (count($caract) > 3): ?>
                  <li class="mas">+ <?= count($caract) - 3 ?> más en «Ver detalles»</li>
                <?php endif; ?>
              </ul>
            <?php elseif ($info): ?>
              <p class="tienda-card__incluye"><i class="<?= $info[1] ?>"></i> <?= $info[2] ?></p>
            <?php endif; ?>

            <div class="tienda-card__pie">
              <div class="tienda-card__precio">
                <span class="importe"><?= htmlspecialchars($s['precio']) ?></span>
                <span class="periodo"><?= htmlspecialchars($s['periodo']) ?></span>
                <span class="tipo-pago"><?= htmlspecialchars($etiquetaPeriodo($s['periodo'])) ?></span>
              </div>

              <div class="servicio-card__acciones tienda-card__acciones">
                <?php if ($contratado): ?>
                  <a href="<?= $urlServ ?>" class="btnRegistrarse"><i class="fa-solid fa-arrow-right"></i> Ir al servicio</a>
                <?php else: ?>
                  <form action="<?= $urlPago ?>" method="post">
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($s['product_id']) ?>">
                    <button type="submit" class="btnRegistrarse">Proceder con el pago</button>
                  </form>
                <?php endif; ?>

                <button
                  type="button"
                  class="btn-info-servicio"
                  data-titulo="<?= htmlspecialchars($s['nombre']) ?>"
                  data-descripcion="<?= htmlspecialchars($s['descripcion_larga']) ?>"
                  data-caracteristicas='<?= htmlspecialchars(json_encode($caract, JSON_UNESCAPED_UNICODE)) ?>'
                  data-icono="<?= htmlspecialchars($s['icono']) ?>"
                  data-tipo-etiqueta="<?= $info ? htmlspecialchars($info[0]) : '' ?>"
                  data-tipo-icono="<?= $info ? htmlspecialchars($info[1]) : '' ?>"
                  data-tipo-clave="<?= htmlspecialchars($tipo) ?>"
                  data-modalidad="<?= htmlspecialchars($etiquetaPeriodo($s['periodo'])) ?>"
                  data-periodo="<?= htmlspecialchars($s['periodo']) ?>"
                  data-contratado="<?= $contratado ? '1' : '' ?>"
                  aria-label="Ver detalles de <?= htmlspecialchars($s['nombre']) ?>"
                  title="Ver detalles"
                >
                  <i class="fa-solid fa-circle-info"></i>
                </button>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>

      <p class="tienda-sin-resultados" id="tiendaSinResultados" hidden>No hay servicios de este tipo por ahora.</p>

      <?php endif; ?>

      <!-- Garantías -->
      <section class="tienda-garantias">
        <div class="tienda-garantia" data-reveal style="--i: 0">
          <i class="fa-solid fa-shield-halved"></i>
          <div><strong>Pago seguro</strong><span>El pago se hace en la página de Stripe; nosotros nunca vemos los datos de tu tarjeta.</span></div>
        </div>
        <div class="tienda-garantia" data-reveal style="--i: 1">
          <i class="fa-solid fa-bolt"></i>
          <div><strong>Acceso al terminar</strong><span>Al completar el pago, el servicio aparece en «Mis Servicios» en la barra lateral.</span></div>
        </div>
        <div class="tienda-garantia" data-reveal style="--i: 2">
          <i class="fa-solid fa-ticket"></i>
          <div><strong>Códigos promocionales</strong><span>En la página de pago puedes introducir un código de descuento si tienes uno.</span></div>
        </div>
        <div class="tienda-garantia" data-reveal style="--i: 3">
          <i class="fa-solid fa-headset"></i>
          <div><strong>¿Dudas?</strong><span>Escríbenos por el chat o por WhatsApp antes de decidir. <a href="#" data-abrir-chat>Abrir el chat</a></span></div>
        </div>
      </section>

    </div>
  </div>
</main>

<!-- Modal de información del servicio (uno solo, reutilizado por todas las tarjetas). Solo informa: sin precio ni botones de compra. -->
<div class="modal fade" id="modalServicioInfo" tabindex="-1" aria-labelledby="modalServicioTitulo" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <div class="modal-servicio-cabeza">
          <div class="modal-servicio-icono" id="modalServicioIcono"><i class="fa-solid fa-star"></i></div>
          <div class="modal-servicio-titulos">
            <h5 class="modal-title" id="modalServicioTitulo"></h5>
            <div class="modal-servicio-chips" id="modalServicioChips"></div>
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p id="modalServicioDescripcion" class="modal-servicio-lead"></p>

        <div id="modalServicioAviso" class="modal-servicio-aviso d-none">
          <i class="fa-solid fa-circle-check"></i>
          <span>Ya tienes este servicio contratado. Lo encuentras en «Mis Servicios», en la barra lateral.</span>
        </div>

        <section class="modal-seccion">
          <h6><i class="fa-solid fa-list-check"></i> Qué incluye</h6>
          <ul id="modalServicioCaracteristicas" class="servicio-caracteristicas"></ul>
        </section>

        <section class="modal-seccion" id="modalSeccionRecibir">
          <h6><i class="fa-solid fa-gift"></i> Qué vas a recibir</h6>
          <p id="modalServicioRecibir"></p>
        </section>

        <section class="modal-seccion">
          <h6><i class="fa-solid fa-route"></i> Cómo funciona</h6>
          <ol class="modal-pasos">
            <li><strong>Contrátalo</strong><span>Pulsa «Proceder con el pago» en la tarjeta del servicio y completa el pago en la página segura de Stripe.</span></li>
            <li><strong>Aparece en tu cuenta</strong><span>Al terminar, el servicio se añade a «Mis Servicios» en la barra lateral.</span></li>
            <li><strong>Entra y úsalo</strong><span id="modalServicioPasoUso">Abre el servicio desde la barra lateral cuando quieras.</span></li>
          </ol>
        </section>
      </div>
    </div>
  </div>
</div>
<script src="<?= LINKS_PATH . '/js/code_js_jquerys/tienda.js?v=' . (@filemtime(__DIR__ . '/../../../public/js/code_js_jquerys/tienda.js') ?: 1) ?>" defer></script>
