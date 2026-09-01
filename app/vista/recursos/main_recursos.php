<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h3 class="mb-0">Tienda</h3>
        </div>
      </div>
    </div>
  </div>
  <div class="app-content">
    <div class="container-fluid">

      <div class="servicios-grid">
        <?php foreach ($servicios as $s): ?>
          <div class="servicio-card">
            <div class="servicio-card__icon">
              <i class="<?= $s['icono'] ?>"></i>
            </div>
            <h3><?= htmlspecialchars($s['nombre']) ?></h3>
            <p class="servicio-card__desc"><?= htmlspecialchars($s['descripcion_corta']) ?></p>
            <div class="servicio-card__precio">
              <?= htmlspecialchars($s['precio']) ?>
              <span class="periodo"><?= htmlspecialchars($s['periodo']) ?></span>
            </div>
            <div class="servicio-card__acciones">
              <form action="<?= BASE_PATH . "/pago?mode=" . $s['mode'] ?>" method="post">
                <input type="hidden" name="product_id" value="<?= $s['product_id'] ?>">

                <button type="submit" class="btnRegistrarse">
                  Proceder con el pago
                </button>
              </form>

              <button
                type="button"
                class="btn-info-servicio"
                data-titulo="<?= htmlspecialchars($s['nombre']) ?>"
                data-descripcion="<?= htmlspecialchars($s['descripcion_larga']) ?>"
                data-precio="<?= htmlspecialchars($s['precio'] . ' ' . $s['periodo']) ?>"
                data-caracteristicas='<?= htmlspecialchars(json_encode($s['caracteristicas'], JSON_UNESCAPED_UNICODE)) ?>'
                aria-label="Información de <?= htmlspecialchars($s['nombre']) ?>"
                title="Más información"
              >
                <i class="fa-solid fa-circle-info"></i>
              </button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</main>

<!-- Modal de información del servicio (uno solo, reutilizado por las 4 cards) -->
<div class="modal fade" id="modalServicioInfo" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalServicioTitulo"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p id="modalServicioDescripcion"></p>
        <ul id="modalServicioCaracteristicas" class="servicio-caracteristicas"></ul>
        <div class="modal-precio-final" id="modalServicioPrecio"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
        <form action="<?= BASE_PATH . "/pago?mode=" . $s['mode'] ?>" method="post">
          <input type="hidden" name="product_id" value="<?= $s['product_id'] ?>">

          <button id="modalServicioBtnPagar" type="submit" class="btnRegistrarse">
            Proceder con el pago
          </button>
        </form>
      </div>
    </div>
  </div>
</div>


