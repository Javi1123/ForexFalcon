<?php /* min/pie.php — Footer rediseño ForexFalcon */ ?>

<footer class="container-fluid bg-azul pt-5 pb-3">
  <div class="container">

    <div class="row g-4 pb-4">

      <!-- Logo + tagline + redes -->
      <div class="col-12 col-md-4 col-lg-3">
        <div class="footer-logo-text">ForexFalcon</div>
        <p class="footer-tagline">
          Tu academia de trading hacia la libertad financiera. Sin promesas falsas, sin atajos.
        </p>
        <div class="footer-social">
          <a href="https://www.instagram.com/forex_falcon_oficial?igsh=Y3NiZm9oMWc0cHho" target="_blank" rel="noopener" title="Instagram">
            <i class="fab fa-instagram"></i>
          </a>
          <a href="https://www.tiktok.com/@forexfalcon1?_r=1&_t=ZN-94PCJqE28G6" target="_blank" rel="noopener" title="TikTok">
            <i class="fab fa-tiktok"></i>
          </a>
          <a href="https://t.me/ForexFalconn" target="_blank" rel="noopener" title="Telegram">
            <i class="fab fa-telegram"></i>
          </a>
        </div>
      </div>

      <!-- Catálogo -->
      <div class="col-6 col-md-2">
        <p class="footer-col-title">Catálogo</p>
        <div class="footer-col">
          <a href="<?= BASE_PATH . "copytrading" ?>" class="green">CopyTrading</a>
          <a href="<?= BASE_PATH . "mentorias" ?>"   class="green">Mentorías</a>
          <a href="<?= BASE_PATH . "bots" ?>"        class="green">Bots</a>
          <a href="<?= BASE_PATH . "analisis" ?>"    class="green">Análisis</a>
        </div>
      </div>

      <!-- Compañía -->
      <div class="col-6 col-md-2">
        <p class="footer-col-title">Compañía</p>
        <div class="footer-col">
          <a href="<?= BASE_PATH . "quienes_somos" ?>" class="green">Quiénes somos</a>
          <a href="<?= BASE_PATH . "contacto" ?>"       class="green">Contacto</a>
          <a href=".#FAQs"                              class="green">FAQs</a>
        </div>
      </div>

      <!-- Contacto -->
      <div class="col-12 col-md-4 col-lg-3 ms-lg-auto">
        <p class="footer-col-title">¿Tienes dudas?</p>
        <p class="footer-tagline" style="max-width:none;">
          Escríbenos y te respondemos sin compromiso.
        </p>
        <a href="mailto:info@forexfalcon.com" class="btnRegistrarse mt-3 d-inline-flex" style="font-size:0.88rem; padding:9px 20px;">
          <i class="fas fa-envelope me-2" aria-hidden="true"></i>
          info@forexfalcon.com
        </a>
      </div>

    </div>

    <!-- Divider + copyright -->
    <hr class="footer-divider">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 pb-2">
      <span class="footer-copy">© <?= date('Y') ?> ForexFalcon · Todos los derechos reservados</span>
      <a href="#navegacion" class="footer-copy" style="text-decoration:none;" title="Volver arriba">
        Subir ↑
      </a>
    </div>

  </div>
</footer>