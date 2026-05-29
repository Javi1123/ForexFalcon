<!-- Video Principal -->
<section class="hero ">

  <div class="hero__video-wrap">
    <video autoplay muted loop playsinline preload="auto" aria-hidden="true">
      <source src="<?= LINKS_PATH . "/videos/3.webm" ?>" type="video/webm" />
      <source src="<?= LINKS_PATH . "/videos/3.mp4" ?>" type="video/mp4" />

      <img src="<?= LINKS_PATH . "/imagenes/graficas_inicio_no_video.png" ?>" alt="ForexFalcon hero background" />
    </video>
  </div>

  <!-- OVERLAY OSCURO -->
  <div class="hero__overlay" aria-hidden="true"></div>

  <!-- CONTENIDO -->
  <div class="container">
    <div class="hero__content">

      <h1 class="hero__title fade-up">
        Conviértete en trader
        <span class="accent">HOY MISMO</span>
      </h1>

      <p class="hero__body fade-up">
        En <strong>ForexFalcon</strong> no solo aprendes trading.
        Te acompañamos desde el primer día hasta que operes con total seguridad,
         sin importar tu punto de partida.
      </p>

      <?php if(isset($_SESSION['usuario'])) : ?>
        <a href="<?= BASE_PATH . "recursos" ?>" class="hero__cta fade-up">
          <i class="fa-solid fa-play"></i>
          Ir a mis recursos
        </a>
      <?php else : ?>
        <a href="<?= BASE_PATH . "acciones?tipo=registro" ?>" class="hero__cta fade-up">
          <i class="fa-solid fa-play"></i>
          Comienza ahora
        </a>
      <?php endif; ?>

    </div>
  </div>
  <div class="hero__blur-bottom" id="heroBlurBottom"></div>
</section>