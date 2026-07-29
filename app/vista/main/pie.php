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
          <a href="<?= BASE_PATH . "/copytrading" ?>" class="green">CopyTrading</a>
          <a href="<?= BASE_PATH . "/mentorias" ?>"   class="green">Mentorías</a>
          <a href="<?= BASE_PATH . "/bots" ?>"        class="green">Bots</a>
          <a href="<?= BASE_PATH . "/analisis" ?>"    class="green">Análisis</a>
        </div>
      </div>

      <!-- Compañía -->
      <div class="col-6 col-md-2">
        <p class="footer-col-title">Compañía</p>
        <div class="footer-col">
          <a href="<?= BASE_PATH . "/quienes_somos" ?>" class="green">Quiénes somos</a>
          <a href=".#FAQs"                              class="green">FAQs</a>
        </div>
      </div>

      <!-- Contacto -->
      <div class="col-12 col-md-4 col-lg-3 ms-lg-auto" id="contacto">
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
      <a href="#subir" class="footer-copy" style="text-decoration:none;" title="Volver arriba">
        Subir ↑
      </a>
    </div>

  </div>
</footer>

<?php if (isset($_SESSION['toast'])): 
  $toast = $_SESSION['toast'];
  unset($_SESSION['toast']); // Se elimina para que no vuelva a aparecer al refrescar
?>

<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
  <div id="registroToast" class="toast align-items-center text-white <?= $toast['tipo'] === 'error' ? 'bg-danger' : 'bg-success' ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        <i class="fa-solid <?= $toast['tipo'] === 'error' ? 'fa-circle-exclamation' : 'fa-circle-check' ?> me-2"></i>
        <?= htmlspecialchars($toast['mensaje']) ?>
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const toastEl = document.getElementById('registroToast');
    if (toastEl) {
      const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
      toast.show();
    }
  });
</script>

<?php endif; ?>