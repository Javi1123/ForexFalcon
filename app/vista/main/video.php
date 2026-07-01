<?php /* min/video.php — Hero rediseño ForexFalcon */ ?>

<section class="hero">

  <!-- Vídeo de fondo -->
  <div class="hero__video-wrap">
    <video autoplay muted loop playsinline preload="auto" aria-hidden="true">
      <source src="<?= LINKS_PATH . "/videos/3.webm" ?>" type="video/webm" />
      <source src="<?= LINKS_PATH . "/videos/3.mp4" ?>"  type="video/mp4"  />
      <img src="<?= LINKS_PATH . "/imagenes/graficas_inicio_no_video.png" ?>" alt="ForexFalcon hero background" />
    </video>
  </div>

  <!-- Overlay oscuro -->
  <div class="hero__overlay" aria-hidden="true"></div>

  <!-- Grid decorativo -->
  <div class="hero__grid" aria-hidden="true"></div>

  <!-- Orbes decorativos -->
  <div class="hero__orb"  aria-hidden="true"></div>
  <div class="hero__orb2" aria-hidden="true"></div>

  <!-- Contenido -->
  <div class="container">
    <div class="hero__content">

      <!-- Pill eyebrow -->
      <div class="hero__eyebrow fade-up">
        <span class="hero__eyebrow-dot" aria-hidden="true"></span>
        Academia de trading profesional
      </div>

      <!-- Título -->
      <h1 class="hero__title fade-up">
        Domina los mercados
        <span class="accent">desde hoy.</span>
      </h1>

      <!-- Subtítulo -->
      <p class="hero__body fade-up">
        En <strong>ForexFalcon</strong> no solo aprendes trading.
        Te acompañamos desde el primer día hasta que operes con total
        seguridad, sin importar tu punto de partida.
      </p>

      <!-- CTA -->
      <?php if(isset($_SESSION['usuario'])) : ?>
        <a href="<?= BASE_PATH . "/recursos" ?>" class="hero__cta fade-up">
          <i class="fa-solid fa-play" aria-hidden="true"></i>
          Ir a mis recursos
        </a>
      <?php else : ?>
        <a href="<?= BASE_PATH . "/acciones?tipo=registro" ?>" class="hero__cta fade-up">
          <i class="fa-solid fa-play" aria-hidden="true"></i>
          Comienza ahora
        </a>
      <?php endif; ?>

      <!-- Stats de credibilidad -->
      <div class="hero__stats fade-up">
        <div>
          <div class="hero__stat-num">+1.200</div>
          <div class="hero__stat-label">Traders activos</div>
        </div>
        <div>
          <div class="hero__stat-num">97%</div>
          <div class="hero__stat-label">Satisfacción</div>
        </div>
        <div>
          <div class="hero__stat-num">24/7</div>
          <div class="hero__stat-label">Bots operando</div>
        </div>
      </div>

    </div>
  </div>

  <div class="hero__blur-bottom" id="heroBlurBottom"></div>
</section>