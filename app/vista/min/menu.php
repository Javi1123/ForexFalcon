<header class="col-xl-auto" id="navegacion">
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <!-- Logo -->
      <a href="<?= BASE_PATH ?>" class="ps-3">
        <img class="zoom" src="<?= LINKS_PATH . "/imagenes/logo.png" ?>" alt="Logo forexfalcon" width="70px">
      </a>

      <!-- Grupo móvil: botones + hamburguesa (visible solo en pantallas pequeñas) -->
      <div class="d-flex d-lg-none align-items-center">
        <button type="button" class="btn btnInicioSesion fw-bold me-2">Iniciar sesion</button>
        <button type="button" class="btn btnRegistrarse fw-bold me-2">Registrarse</button>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#barratop" aria-controls="barratop" aria-expanded="false" aria-label="Toggle navigation">
          <i class="fa-solid fa-bars orange"></i>
        </button>
      </div>

      <!-- Contenido colapsable -->
      <div class="collapse navbar-collapse" id="barratop">
        <!-- Menú centrado -->
        <ul class="navbar-nav mx-auto">
          <li class="nav-item dropdown">
            <button id="nosotros" class="text-white nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Servicios
            </button>
            <ul class="dropdown-menu" id="listaNosotros">
              <li><a class="dropdown-item ps-2 text-white text-decoration-none" href="<?= BASE_PATH . "/copytrading" ?>">CopyTrading</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item ps-2 text-white text-decoration-none" href="<?= BASE_PATH . "/mentorias" ?>">Mentorias</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item ps-2 text-white text-decoration-none" href="<?= BASE_PATH . "/bots" ?>">Bots</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item ps-2 text-white text-decoration-none" href="<?= BASE_PATH . "/analisis" ?>">Análisis</a></li>
            </ul>
          </li>
          <li class="nav-item text-center">
            <a class="text-white nav-link" href="<?= BASE_PATH . "/catalogo" ?>">Catálogo</a>
          </li>
          <li class="nav-item text-center">
            <a class="text-white nav-link" href="<?= BASE_PATH . "/quienes_somos" ?>">Quiénes somos</a>
          </li>
          <li class="nav-item text-center">
            <a class="text-white nav-link" href="<?= BASE_PATH . "/ayuda" ?>">Ayuda</a>
          </li>
        </ul>

        <!-- Botones de escritorio (ocultos en móvil) -->
        <div class="d-none d-lg-flex ms-auto">
          <button type="button" class="btn btnInicioSesion fw-bold me-2">Iniciar sesion</button>
          <button type="button" class="btn btnRegistrarse fw-bold">Registrarse</button>
        </div>
      </div>
    </div>
  </nav>
</header>