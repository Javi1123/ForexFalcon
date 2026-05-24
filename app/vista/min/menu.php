<header class="col-xl-auto" id="navegacion">
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">

      <!-- Logo -->
      <a href="<?= BASE_PATH ?>" class="ms-3">
        <img src="<?= LINKS_PATH . "/imagenes/logo.png" ?>" alt="Logo forexfalcon" width="65px">
      </a>

      <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fa-solid fa-bars orange"></i>
      </button>

      <!-- Offcanvas (móvil) -->
      <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header">
          <span class="h2 fw-bold green">Forexfalcon</span>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">

          <!-- 1. Botones arriba en columna -->
          <div class="d-flex flex-column gap-2 mb-4 d-lg-none">
            <a class="btn btnInicioSesion fw-bold text-center" href="<?= BASE_PATH . "/acciones" ?>">Iniciar sesion</a>
            <a class="btn btnRegistrarse fw-bold text-center" href="<?= BASE_PATH . "/acciones" ?>">Registrarse</a>
          </div>

          <!-- 2. Links de navegación en el centro -->
          <ul class="navbar-nav flex-grow-1 justify-content-evenly">
            <li class="nav-item">
              <a class="nav-link text-white" id="btnCatalogo" href=".#catalogo">Catálogo</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white" id="btnEsencia" href="#esencia">Esencia</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-white" id="btnCompañia" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Compañia
                <i class="fa-solid fa-chevron-down"></i>
              </a>
              <ul class="dropdown-menu" id="serviciosMovil">
                <li><a class="dropdown-item ps-2 text-white text-decoration-none zona-sobre" href="<?= BASE_PATH . "/quienes_somos" ?>">Quienes somos</a></li>
                <hr class="dropdown-divider">
                <li><a class="dropdown-item ps-2 text-white text-decoration-none zona-sobre" href="<?= BASE_PATH . "/donde_estamos" ?>">Donde estamos</a></li>
                <hr class="dropdown-divider">
                <li><a class="dropdown-item ps-2 text-white text-decoration-none zona-centro" href="<?= BASE_PATH . "/contacto" ?>">Contacto</a></li>
                <hr class="dropdown-divider">
                <li><a class="dropdown-item ps-2 text-white text-decoration-none zona-centro" id="btnFAQ" href="#FAQs">FAQs</a></li>
              </ul>
            </li>
          </ul>

          <!-- 3. Botones desktop a la derecha (ocultos en móvil) -->
          <div class="d-none d-lg-flex align-items-center">
            <a class="btn btnInicioSesion fw-bold me-2" href="<?= BASE_PATH . "/acciones" ?>">Iniciar sesion</a>
            <a class="btn btnRegistrarse fw-bold me-2" href="<?= BASE_PATH . "/acciones" ?>">Registrarse</a>
          </div>

        </div>
      </div>
    </div>
  </nav>
</header>