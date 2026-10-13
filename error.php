<?php
/* error.php — Página de errores ForexFalcon (404, 403, 500...) — autónoma, sin includes */

if (session_status() === PHP_SESSION_NONE) session_start();


/* Código de error: lo da Apache (REDIRECT_STATUS) o ?codigo=XXX */
$codigo = (int)($_GET['codigo'] ?? $_SERVER['REDIRECT_STATUS'] ?? 404);

$errores = [
    400 => ['Solicitud incorrecta',   'La petición no se ha podido entender. Revisa la dirección e inténtalo de nuevo.'],
    401 => ['Acceso no autorizado',   'Necesitas iniciar sesión para ver esta página.'],
    403 => ['Acceso prohibido',       'No tienes permiso para acceder a este recurso.'],
    404 => ['Página no encontrada',   'La página que buscas no existe, ha cambiado de sitio o la dirección está mal escrita.'],
    500 => ['Error del servidor',     'Algo ha fallado de nuestro lado. Ya estamos en ello, inténtalo de nuevo en unos minutos.'],
    503 => ['Servicio no disponible', 'Estamos en mantenimiento. Volvemos enseguida.'],
];
if (!isset($errores[$codigo])) $codigo = 404;
[$tituloError, $textoError] = $errores[$codigo];

http_response_code($codigo);
header('X-Robots-Tag: noindex, nofollow');

$DESCRIPCION = "Error $codigo - forexfalcon.com";
$CLAVES      = "forexfalcon, error $codigo";
$AUTOR       = "ForexFalcon";
$TITULO      = "Error $codigo | ForexFalcon";
?>
<!DOCTYPE html>
<html lang="es-es" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="robots" content="noindex, nofollow">
  <meta name="description" content="<?= $DESCRIPCION ?>">
  <meta name="keywords" content="<?= $CLAVES ?>">
  <meta name="author" content="<?= $AUTOR ?>">
  <title><?= $TITULO ?></title>
  <link rel="shortcut icon" href="/forexfalcon/public/imagenes/favicon.ico">

  <!-- Bootstrap CSS + JS -->
  <link href="/forexfalcon/public/css/bootstrap.min.css" rel="stylesheet">
  <script src="/forexfalcon/public/js/bootstrap.bundle.min.js"></script>

  <!-- Font Awesome -->
  <script src="/forexfalcon/public/js/all.min.js"></script>

  <!-- jQuery -->
  <script src="/forexfalcon/public/js/jquery-4.0.0.min.js"></script>

  <!-- SweetAlert2 -->
  <script src="/forexfalcon/public/js/sweetalert2.all.min.js"></script>

  <!-- CSS y JS ForexFalcon -->
  <link rel="stylesheet" href="/forexfalcon/public/css/main.css">
  <script src="/forexfalcon/public/js/code_js_jquerys/forexfalcon.js" defer></script>
  <script src="/forexfalcon/public/js/code_js_jquerys/frankfurter.js" defer></script>
  <script src="/forexfalcon/public/js/code_js_jquerys/gestion_de_nav.js" defer></script>
  <!-- Ajuste a pantalla completa (solo página de error) -->
  <style>
    html { height: 100%; }
    body { min-height: 100vh; min-height: 100dvh; display: flex; flex-direction: column; margin: 0; }

    /* El hero ocupa todo el espacio libre entre menú y pie */
    body > .hero--error {
      flex: 1 0 auto;
      height: auto !important;
      min-height: 0 !important;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .hero--error > .container { width: 100%; }
    .hero--error .hero__content { max-width: 760px; }

    /* Tipografía que escala con el ancho y el alto de la pantalla */
    .error-code {
      font-size: clamp(4rem, min(20vw, 24vh), 12rem);
      font-weight: 800; line-height: 1; color: #FB8E37;
    }
    .hero--error .hero__title { font-size: clamp(1.7rem, 5vw, 3.5rem); }
    .hero--error .hero__body  { font-size: clamp(.95rem, 2.2vw, 1.15rem); }

    /* Pantallas bajas (móvil en horizontal): compactar */
    @media (max-height: 560px) {
      .hero--error .hero__eyebrow { display: none; }
      .hero--error .hero__title, .hero--error .hero__body { margin-bottom: .5rem; }
    }
  </style>

</head>

<body>
<div id="subir"></div>

<!-- Toast cookies -->
<div aria-live="polite" aria-atomic="true" class="bg-body-secondary position-relative bd-example-toasts rounded-3">
  <div class="toast-container p-3 position-fixed bottom-0 end-0" id="toastPlacement">
    <div class="toast fade w-100" id="toastCookies" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false" style="max-width: 600px;z-index: 2000;">
      <div class="toast-header orange-background">
        <span class="me-auto text-white fw-bold"><i class="fa-solid fa-cookie me-2"></i> Bienvenidos !!!</span>
        <button type="button" id="btnCookies" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body black-background white">
        Este <strong class="red">portal web</strong> únicamente utiliza cookies propias con finalidad técnica, no cede datos de carácter personal de los usuarios sin su conocimiento.
        <p class="mb-0 small fst-italic orange">Sin embargo, contiene enlaces a sitios web de terceros con políticas de privacidad ajenas a este portal web que usted podrá decidir si acepta o no cuando acceda a ellos.</p>
      </div>
    </div>
  </div>
</div>

<!-- ═════════ MENÚ ═════════ -->
<div class="ticker-bar" id="tasasMoneda">
  <div class="ticker-track"></div>
</div>
<header class="col-xl-auto" id="navegacion">
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">

      <a href="/forexfalcon/" class="ms-3">
        <img src="/forexfalcon/public/imagenes/logo.png" alt="Logo forexfalcon" width="65px">
      </a>

      <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fa-solid fa-bars orange"></i>
      </button>

      <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header">
          <span class="h2 fw-bold green">Forexfalcon</span>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">

          <div class="d-flex flex-column gap-2 mb-4 d-lg-none">
            <a class="btn btnInicioSesion fw-bold text-center" href="/forexfalcon/acciones?tipo=inicio">Iniciar sesion</a>
            <a class="btn btnRegistrarse fw-bold text-center" href="/forexfalcon/acciones?tipo=registro">Registrarse</a>
          </div>

          <ul class="navbar-nav flex-grow-1 justify-content-evenly">
            <li class="nav-item">
              <a class="nav-link hover-link text-white" id="btnEsencia" href="/forexfalcon/#esencia">Esencia</a>
            </li>
            <li class="nav-item">
              <a class="nav-link hover-link text-white" id="btnCatalogo" href="/forexfalcon/#catalogo">Catálogo</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link hover-link dropdown-toggle text-white" id="btnCompañia" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Compañia
                <i class="fa-solid fa-chevron-down"></i>
              </a>
              <ul class="dropdown-menu" id="serviciosMovil">
                <li><a class="dropdown-item ps-2 text-white text-decoration-none zona-sobre" href="/forexfalcon/quienes_somos">Quienes somos</a></li>
                <hr class="dropdown-divider">
                <li><a class="dropdown-item ps-2 text-white text-decoration-none zona-centro" href="#contacto">Contacto</a></li>
                <hr class="dropdown-divider">
                <li><a class="dropdown-item ps-2 text-white text-decoration-none zona-centro" id="btnFAQ" href="/forexfalcon/#FAQs">FAQs</a></li>
              </ul>
            </li>
          </ul>

          <div class="d-none d-lg-flex align-items-center">
            <a class="btn btnInicioSesion fw-bold me-2" href="/forexfalcon/acciones?tipo=inicio">Iniciar sesion</a>
            <a class="btn btnRegistrarse fw-bold me-2" href="/forexfalcon/acciones?tipo=registro">Registrarse</a>
          </div>

        </div>
      </div>
    </div>
  </nav>
</header>

<!-- ═════════ HERO DE ERROR ═════════ -->
<section class="hero hero--error">

  <div class="hero__overlay" aria-hidden="true"></div>
  <div class="hero__grid"    aria-hidden="true"></div>
  <div class="hero__orb"     aria-hidden="true"></div>
  <div class="hero__orb2"    aria-hidden="true"></div>

  <div class="container">
    <div class="hero__content text-center mx-auto">

      <div class="hero__eyebrow fade-up">
        <span class="hero__eyebrow-dot" aria-hidden="true"></span>
        Error <?= $codigo ?>
      </div>

      <div class="error-code fade-up">
        <?= $codigo ?>
      </div>

      <h1 class="hero__title fade-up">
        <?= htmlspecialchars($tituloError) ?>
        <span class="accent">¡Operación fallida!</span>
      </h1>

      <p class="hero__body fade-up d-inline-block">
        <?= htmlspecialchars($textoError) ?>
      </p>

    </div>
  </div>

  <div class="hero__blur-bottom" id="heroBlurBottom"></div>
</section>

<!-- ═════════ PIE ═════════ -->
<footer class="container-fluid bg-azul pt-5 pb-3">
  <div class="container">

    <div class="row g-4 pb-4">

      <div class="col-12 col-md-4 col-lg-3">
        <div class="footer-logo-text">ForexFalcon</div>
        <p class="footer-tagline">
          Tu academia de trading hacia la libertad financiera. Sin promesas falsas, sin atajos.
        </p>
        <div class="footer-social">
          <a href="https://www.instagram.com/forex_falcon_oficial?igsh=Y3NiZm9oMWc0cHho" target="_blank" rel="noopener" title="Instagram"><i class="fab fa-instagram"></i></a>
          <a href="https://www.tiktok.com/@forexfalcon1?_r=1&_t=ZN-94PCJqE28G6" target="_blank" rel="noopener" title="TikTok"><i class="fab fa-tiktok"></i></a>
          <a href="https://t.me/ForexFalconn" target="_blank" rel="noopener" title="Telegram"><i class="fab fa-telegram"></i></a>
        </div>
      </div>

      <div class="col-6 col-md-2">
        <p class="footer-col-title">Catálogo</p>
        <div class="footer-col">
          <a href="/forexfalcon/copytrading" class="green">CopyTrading</a>
          <a href="/forexfalcon/mentorias"   class="green">Mentorías</a>
          <a href="/forexfalcon/bots"        class="green">Bots</a>
          <a href="/forexfalcon/analisis"    class="green">Análisis</a>
        </div>
      </div>

      <div class="col-6 col-md-2">
        <p class="footer-col-title">Compañía</p>
        <div class="footer-col">
          <a href="/forexfalcon/quienes_somos" class="green">Quiénes somos</a>
          <a href="/forexfalcon/#FAQs"              class="green">FAQs</a>
        </div>
      </div>

      <div class="col-12 col-md-4 col-lg-3 ms-lg-auto" id="contacto">
        <p class="footer-col-title">¿Tienes dudas?</p>
        <p class="footer-tagline" style="max-width:none;">Escríbenos y te respondemos sin compromiso.</p>
        <a href="mailto:info@forexfalcon.com" class="btnRegistrarse mt-3 d-inline-flex" style="font-size:0.88rem; padding:9px 20px;">
          <i class="fas fa-envelope me-2" aria-hidden="true"></i>
          info@forexfalcon.com
        </a>
      </div>

    </div>

    <hr class="footer-divider">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 pb-2">
      <span class="footer-copy">© <?= date('Y') ?> ForexFalcon · Todos los derechos reservados</span>
      <a href="#subir" class="footer-copy" style="text-decoration:none;" title="Volver arriba">Subir ↑</a>
    </div>

  </div>
</footer>

</body>
</html>