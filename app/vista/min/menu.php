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
          <ul class="navbar-nav flex-grow-1 justify-content-evenly">
            <li class="nav-item">
              <a class="nav-link text-white" href=".#catalogo">Catálogo</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white" href="#esencia">Esencia</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Compañia
              </a>
              <ul class="dropdown-menu" id="serviciosMovil">
                <li><a class="dropdown-item ps-2 text-white text-decoration-none" href="<?= BASE_PATH . "/quienes_somos" ?>">Quienes somos</a></li>
                <hr class="dropdown-divider">
                <li><a class="dropdown-item ps-2 text-white text-decoration-none" href="<?= BASE_PATH . "/mentorias" ?>">Donde estamos</a></li>
                <hr class="dropdown-divider">
                <li><a class="dropdown-item ps-2 text-white text-decoration-none" href="<?= BASE_PATH . "/contacto" ?>">Contacto</a></li>
                <hr class="dropdown-divider">
                <li><a class="dropdown-item ps-2 text-white text-decoration-none" href="#FAQs">FAQs</a></li>
              </ul>
            </li>
          </ul>

          <div class="d-flex align-items-center">
            <a type="button" class="btn btnInicioSesion fw-bold me-2" href="<?= BASE_PATH . "/acciones" ?>">Iniciar sesion</a>
            <a type="button" class="btn btnRegistrarse fw-bold me-2" href="<?= BASE_PATH . "/acciones" ?>">Registrarse</a>
          </div>
          
        </div>
      </div>
    </div>
  </nav>
</header>