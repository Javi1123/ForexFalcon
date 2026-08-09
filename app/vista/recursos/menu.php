<nav class="app-header navbar navbar-expand bg-body">
  <div class="container-fluid">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
          <i class="fa-solid fa-bars"></i>
        </a>
      </li>
      <li class="nav-item d-none d-md-block">
        <a href="<?= BASE_PATH ?>" class="nav-link">Forexfalcon</a>
      </li>
    </ul>

    <ul class="navbar-nav ms-auto">

      <li class="nav-item">
        <a class="nav-link" href="#" data-lte-toggle="fullscreen">
          <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
          <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
        </a>
      </li>

      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
          <span class="user-image rounded-circle shadow avatar-inicial" style="background-color: <?= $colorAvatar ?>;">
            <?= $inicial ?>
          </span>
          <span class="d-none d-md-inline"><?= $_SESSION['nombre'] . " " . $_SESSION['apellido']?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
          <li class="user-header text-bg-primary">
            <span class="rounded-circle shadow avatar-inicial avatar-inicial-lg">
              <?= $inicial ?>
            </span>
            <p>
              Perfil de <?= $_SESSION['nombre'] . " " . $_SESSION['apellido']?>
            </p>
          </li>

          <!-- PONER CONFIGURACIÓN DE PERFIL Y DE MAS COSAS -->

          <li class="user-footer text-center">
            <a href="<?= BASE_PATH . "/logout"?> " class="btn btn-outline-danger">Cerrar sesión</a>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>

<!-- QUIITAR Y PONER EN UN CSS EXTERNO -->
<style>
  .avatar-inicial {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 25px;
  height: 25px;
  color: #fff;
  font-weight: bold;
  font-size: 14px;
  line-height: 1;
}

.avatar-inicial-lg {
  width: 90px;
  height: 90px;
  font-size: 36px;
  background-color: #007bff; /* el grande se queda fijo */
}
</style>

<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <div class="sidebar-brand">
    <a href=" /forexfalcon" class="brand-link">
      <img class="zoom" src="<?= LINKS_PATH . "/imagenes/logo.png" ?>" alt="Logo forexfalcon" width="60px">
    </a>
  </div>
  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation" data-accordion="false" id="navigation">
        <li class="nav-item">
          <a href="<?= BASE_PATH . "/recursos" ?>" class="nav-link">
            <i class="fa-solid fa-shop"></i>
            <p>Tienda</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="fa-solid fa-bars"></i>
            <p>
              ---
            </p>
          </a>
          <!-- Cambiar para los servicios que tiene el usuario -->
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= BASE_PATH . "/sugerenciaTabla" ?>" class="nav-link">
                <i class="fa-solid fa-bars"></i>
                <p>---</p>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</aside>