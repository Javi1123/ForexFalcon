<!-- Pie de pagina -->
<footer class="container-fluid bg-azul pt-3 pb-2">
  <!-- Boton para ir hacia arriba -->
  <div class="position-relative">
    <a href="#navegacion" class="position-absolute top-0 end-0" title="Ir al inicio de esta página">
      <i class="fas fa-chevron-up pe-2 fa-2x text-white"></i>
    </a>
  </div>
  <!-- Posicion de la empresa y imagen de logo -->
  <div class="row text-white">
    <div class="col-12 col-sm-12 col-md-3 col-lg-2 mb-2 text-center"> 
      <img src="<?= LINKS_PATH . "/imagenes/logo.png" ?>" width="250px" class="img-fluid" alt="Certitransporte - Servicios Tecnológicos de Formación"/>
    </div>
    <div class="flex-row col-sm-12 col-md-9 col-lg-10">
      <div class="row p-3">
        <div class="col-4 col-sm-4 col-xl-3">
          <p class="h4 negrita">Catalogo</p>
          <span class="d-block"><a class="green" href="<?= BASE_PATH . "copytrading" ?>">CopyTrading</a></span>
          <span class="d-block"><a class="green" href="<?= BASE_PATH . "mentorias" ?>">Mentorias</a></span>
          <span class="d-block"><a class="green" href="<?= BASE_PATH . "bots" ?>">Bots</a></span>
          <span class="d-block"><a class="green" href="<?= BASE_PATH . "analisis" ?>">Análisis</a></span>
        </div>
        <div class="col-4 col-sm-4 col-xl-3">
          <p class="h4 negrita">Redes</p>
          <span class="d-block"><a class="green" href="https://www.instagram.com/forex_falcon_oficial?igsh=Y3NiZm9oMWc0cHho">Instagram</a></span>
          <span class="d-block"><a class="green" href="https://www.tiktok.com/@forexfalcon1?_r=1&_t=ZN-94PCJqE28G6">TikTok</a></span>
          <span class="d-block"><a class="green" href="https://t.me/ForexFalconn">Telegram</a></span>
        </div>
        <div class="col-4 col-sm-4 col-xl-3">
          <p class="h4 negrita">Nosotros</p>
          <span class="d-block"><a class="green" href="<?= BASE_PATH . "quienes_somos" ?>">Quienes somos</a></span>
          <span class="d-block"><a class="green" href="<?= BASE_PATH . "contacto" ?>">Contacto</a></span>
        </div>
      </div>

    </div>
  </div>
</footer>

