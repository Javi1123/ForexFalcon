<?php
// Iconos que se pueden elegir (Font Awesome gratuito). Si un producto ya tenía otro, el JS lo conserva.
$iconosDisponibles = [
  'fa-solid fa-star'           => 'Estrella',
  'fa-solid fa-copy'           => 'Copiar',
  'fa-solid fa-user-graduate'  => 'Graduado',
  'fa-solid fa-microchip'      => 'Chip',
  'fa-solid fa-gem'            => 'Gema',
  'fa-solid fa-chart-line'     => 'Gráfico',
  'fa-solid fa-robot'          => 'Robot',
  'fa-solid fa-graduation-cap' => 'Birrete',
  'fa-solid fa-rocket'         => 'Cohete',
  'fa-solid fa-bolt'           => 'Rayo',
  'fa-solid fa-shield-halved'  => 'Escudo',
  'fa-solid fa-coins'          => 'Monedas',
  'fa-solid fa-crown'          => 'Corona',
  'fa-solid fa-book'           => 'Libro',
  'fa-solid fa-bell'           => 'Campana',
  'fa-solid fa-handshake'      => 'Apretón de manos',
];
$simboloMoneda = ['eur' => '€', 'usd' => '$', 'gbp' => '£'][defined('CURRENCY') ? CURRENCY : 'eur'] ?? '€';
$sinSincronizar = 0;
foreach (array_merge($activos, $archivados) as $x) {
  if ($x['id_servicio'] === '' || !isset($sync[strtoupper($x['id_servicio'])])) { $sinSincronizar++; }
}
?>
<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row align-items-center">
        <div class="col-sm-8">
          <h3 class="mb-0">
            Administrar servicios
            <?php if ($modoPrueba): ?>
              <span class="badge-modo badge-modo--prueba" title="Estás usando una clave de prueba (sk_test_): no se cobra dinero real.">
                Modo prueba
              </span>
            <?php else: ?>
              <span class="badge-modo badge-modo--real" title="Estás usando la clave real de Stripe: los cambios afectan a la tienda en producción.">
                Modo real
              </span>
            <?php endif; ?>
          </h3>
          <small class="admin-subtitulo">Los cambios se aplican a la vez en Stripe y en tu base de datos.</small>
        </div>
        <div class="col-sm-4 text-sm-end mt-3 mt-sm-0">
          <button type="button" class="btnRegistrarse" id="btnNuevoServicio">
            <i class="fa-solid fa-plus"></i> Nuevo servicio
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="app-content">
    <div class="container-fluid">

      <?php if ($errorStripe): ?>
        <div class="alert alert-danger admin-alerta" role="alert">
          <i class="fa-solid fa-circle-exclamation me-2"></i>
          No se pudieron cargar los servicios de Stripe: <?= htmlspecialchars($errorStripe) ?>
        </div>
      <?php endif; ?>

      <?php if ($sinSincronizar > 0): ?>
        <div class="alert alert-warning admin-alerta" role="alert">
          <i class="fa-solid fa-triangle-exclamation me-2"></i>
          <?= $sinSincronizar ?> servicio<?= $sinSincronizar > 1 ? 's' : '' ?> de Stripe no <?= $sinSincronizar > 1 ? 'están' : 'está' ?>
          en tu tabla <code>Servicios</code>. Hasta sincronizarlo<?= $sinSincronizar > 1 ? 's' : '' ?>, una compra fallaría al guardar la suscripción.
          Usa el botón «Sincronizar con la BD» de la tarjeta.
        </div>
      <?php endif; ?>

      <div class="servicios-grid" id="gridActivos">
        <?php foreach ($activos as $s): $archivado = false; include __DIR__ . '/tarjeta_servicio.php'; endforeach; ?>
      </div>
      <?php if (!$activos && !$errorStripe): ?>
        <p class="admin-vacio">Todavía no hay servicios activos. Crea el primero con «Nuevo servicio».</p>
      <?php endif; ?>

      <?php if ($archivados): ?>
        <h4 class="admin-seccion"><i class="fa-solid fa-box-archive"></i> Archivados <span><?= count($archivados) ?></span></h4>
        <p class="admin-subtitulo">No salen en la tienda. Puedes restaurarlos cuando quieras.</p>
        <div class="servicios-grid" id="gridArchivados">
          <?php foreach ($archivados as $s): $archivado = true; include __DIR__ . '/tarjeta_servicio.php'; endforeach; ?>
        </div>
      <?php endif; ?>

    </div>
  </div>
</main>

<!-- ═════════ Modal: crear / editar servicio ═════════ -->
<div class="modal fade" id="modalServicio" tabindex="-1" aria-labelledby="svcTitulo" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <form class="modal-content" id="formServicio" novalidate>
      <div class="modal-header">
        <h5 class="modal-title" id="svcTitulo">Nuevo servicio</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <div class="admin-error" id="svcError" role="alert" hidden></div>

        <div class="row g-3">
          <div class="col-md-8">
            <label class="form-label" for="svcNombre">Nombre *</label>
            <input type="text" class="form-control" id="svcNombre" maxlength="100" required>
          </div>
          <div class="col-md-4">
            <label class="form-label" for="svcCodigo">Código</label>
            <input type="text" class="form-control text-uppercase" id="svcCodigo" maxlength="20"
                   pattern="[A-Za-z0-9]{1,20}" autocomplete="off" aria-describedby="svcCodigoAyuda">
            <div class="form-text" id="svcCodigoAyuda">Letras y números. No se puede cambiar después.</div>
          </div>

          <div class="col-12">
            <label class="form-label" for="svcCorta">Descripción corta <small>(sale en la tarjeta)</small></label>
            <textarea class="form-control" id="svcCorta" rows="2" maxlength="300"></textarea>
          </div>

          <div class="col-12">
            <label class="form-label" for="svcLarga">Descripción larga <small>(sale al pulsar «información»)</small></label>
            <textarea class="form-control" id="svcLarga" rows="3" maxlength="500"></textarea>
            <div class="form-text"><span id="svcLargaContador">0</span>/500</div>
          </div>

          <div class="col-md-6">
            <label class="form-label" for="svcIcono">Icono</label>
            <div class="icono-selector">
              <span class="icono-prev" id="svcIconoPrev" aria-hidden="true"></span>
              <select class="form-select" id="svcIcono">
                <?php foreach ($iconosDisponibles as $clase => $nombre): ?>
                  <option value="<?= htmlspecialchars($clase, ENT_QUOTES) ?>"><?= htmlspecialchars($nombre) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="col-12">
            <label class="form-label" for="svcCaracteristicas">Características <small>(una por línea)</small></label>
            <textarea class="form-control" id="svcCaracteristicas" rows="5" aria-describedby="svcCaractAyuda"></textarea>
            <div class="form-text" id="svcCaractAyuda">
              <span id="svcCaractContador">0</span>/15 características · máximo 80 caracteres cada una
            </div>
          </div>

          <!-- Solo al crear: el precio se cambia después con su propio botón -->
          <div class="col-12" id="svcGrupoPrecio">
            <hr class="my-1">
            <div class="row g-3 mt-0">
              <div class="col-md-6">
                <label class="form-label" for="svcImporte">Precio *</label>
                <div class="input-group">
                  <input type="text" class="form-control" id="svcImporte" inputmode="decimal" placeholder="49,90" autocomplete="off">
                  <span class="input-group-text"><?= htmlspecialchars($simboloMoneda) ?></span>
                </div>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="svcTipo">Tipo de cobro *</label>
                <select class="form-select" id="svcTipo">
                  <option value="mensual">Suscripción mensual</option>
                  <option value="semanal">Suscripción semanal</option>
                  <option value="anual">Suscripción anual</option>
                  <option value="unico">Pago único</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btnRegistrarse" id="svcGuardar">Guardar</button>
      </div>
    </form>
  </div>
</div>

<!-- ═════════ Modal: cambiar precio ═════════ -->
<div class="modal fade" id="modalPrecio" tabindex="-1" aria-labelledby="prcTitulo" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" id="formPrecio" novalidate>
      <div class="modal-header">
        <h5 class="modal-title" id="prcTitulo">Cambiar precio</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <div class="admin-error" id="prcError" role="alert" hidden></div>

        <p class="mb-3"><strong id="prcServicio"></strong><br>
          <span class="admin-subtitulo">Precio actual: <span id="prcActual"></span></span></p>

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label" for="prcImporte">Nuevo precio *</label>
            <div class="input-group">
              <input type="text" class="form-control" id="prcImporte" inputmode="decimal" placeholder="49,90" autocomplete="off">
              <span class="input-group-text"><?= htmlspecialchars($simboloMoneda) ?></span>
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label" for="prcTipo">Tipo de cobro *</label>
            <select class="form-select" id="prcTipo">
              <option value="mensual">Suscripción mensual</option>
              <option value="semanal">Suscripción semanal</option>
              <option value="anual">Suscripción anual</option>
              <option value="unico">Pago único</option>
            </select>
          </div>
        </div>

        <p class="admin-nota mt-3 mb-0">
          <i class="fa-solid fa-circle-info"></i>
          El nuevo precio se aplica a las compras nuevas. Quien ya esté suscrito sigue pagando su precio anterior.
        </p>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btnRegistrarse" id="prcGuardar">Cambiar precio</button>
      </div>
    </form>
  </div>
</div>
