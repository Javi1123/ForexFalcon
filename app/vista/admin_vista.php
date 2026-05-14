<!doctype html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Certitransporte - Admin</title>

    <link rel="shortcut icon" href="<?= LINKS_PATH . "/imagenes/favicon-blanco.ico" ?>">
    <link rel="certiTransporte" type="image/x-icon" href="<?= LINKS_PATH . "/imagenes/favicon-blanco.ico" ?>">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />

    <meta name="title" content="AdminLTE | Dashboard v3" />
    <meta name="author" content="ColorlibHQ" />
    <meta name="description" content="Panel de administración de Certitransporte. Gestiona sugerencias, alumnos, cursos, recibos y matriculados de forma centralizada.">
    <meta name="keywords" content="Certitransporte, administración, gestión de cursos, gestión de alumnos, matriculados, recibos, sugerencias, panel admin, transporte educativo">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"/>

    <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc3/dist/js/adminlte.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc3/dist/css/adminlte.min.css"/>
  
    <!-- Certitransporte -->
    <link rel="stylesheet" href="<?= LINKS_PATH . "/css/forexfalcon.css" ?>">

  </head>

  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
      <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
            <li class="nav-item d-none d-md-block">
              <a href="<?= BASE_PATH ?>" class="nav-link">Certitransporte</a>
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
                <img
                  src="<?= LINKS_PATH . "/imagenes/persona.png" ?>"
                  class="user-image rounded-circle shadow"
                  alt="User Image"
                />
                <span class="d-none d-md-inline"><?= $nombre_completo ?></span>
              </a>
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <li class="user-header text-bg-primary">
                  <img
                    src="<?= LINKS_PATH . "/imagenes/persona.png" ?>"
                    class="rounded-circle shadow"
                    alt="User Image"
                  />
                  <p>
                    <?= $nombre_completo ?>
                    <small>Perfil de administrador</small>
                  </p>
                </li>
                <li class="user-footer text-center">
                  <a href="<?= BASE_PATH . "/logout"?> " class="btn btn-outline-danger">Cerrar sesión</a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
          <a href=" /forexfalcon" class="brand-link">
            <img class="zoom" src="<?= LINKS_PATH . "/imagenes/favicon-blanco.png" ?>" alt="Logo forexfalcon" width="100px">
          </a>
        </div>
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation" data-accordion="false" id="navigation">
              <li class="nav-item">
                <a href="<?= BASE_PATH . "/admin" ?>" class="nav-link">
                  <i class="nav-icon bi bi-house"></i>
                  <p>Inicio</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-table"></i>
                  <p>
                    Tablas
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="<?= BASE_PATH . "/sugerenciaTabla" ?>" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Tabla de sugerencias</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?= BASE_PATH . "/alumnosTabla" ?>" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Tabla de alumnos</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?= BASE_PATH . "/cursosTabla" ?>" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Tabla de cursos</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?= BASE_PATH . "/recibosTabla" ?>" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Tabla de recibos</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?= BASE_PATH . "/matriculadosTabla" ?>" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Tabla de matriculados</p>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </nav>
        </div>
      </aside>
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0">Inicio</h3>
              </div>
            </div>
          </div>
        </div>
        <div class="app-content">
          <div class="container-fluid">

            <div class="row">
              <div class="col-lg-4 col-6 reveal reveal-scale delay-1">
                <div class="small-box text-bg-primary">
                  <div class="inner">
                    <h3><?= $allSugerencias ?></h3>
                    <p>Total de sugerencias</p>
                  </div>
                  <i class="bi bi-exclamation-circle-fill small-box-icon"></i>
                  <a href="<?= BASE_PATH . "/sugerenciaTabla" ?>" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Más información <i class="bi bi-link-45deg"></i>
                  </a>
                </div>
              </div>
              <div class="col-lg-4 col-6 reveal reveal-scale delay-2">
                <div class="small-box text-bg-success">
                  <div class="inner">
                    <h3><?= $allAlumnos ?></h3>
                    <p>Total de alumnos</p>
                  </div>
                  <i class="bi bi-people-fill small-box-icon"></i>
                  <a href="<?= BASE_PATH . "/alumnosTabla" ?>" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Más información <i class="bi bi-link-45deg"></i>
                  </a>
                </div>
              </div>
              <div class="col-lg-4 col-12 reveal reveal-scale delay-3">
                <div class="small-box text-bg-warning">
                  <div class="inner">
                    <h3><?= $allCursos ?></h3>
                    <p>Total de cursos</p>
                  </div>
                  <i class="bi bi-server small-box-icon"></i>
                  <a href="<?= BASE_PATH . "/cursosTabla" ?>" class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                    Más información <i class="bi bi-link-45deg"></i>
                  </a>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-6 col-6 reveal reveal-scale delay-4">
                <div class="small-box text-bg-info">
                  <div class="inner">
                    <h3><?= $allMatriculados ?></h3>
                    <p>Total de matriculados</p>
                  </div>
                  <i class="bi bi-patch-check-fill small-box-icon"></i>
                  <a href="<?= BASE_PATH . "/matriculadosTabla" ?>" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Más información <i class="bi bi-link-45deg"></i>
                  </a>
                </div>
              </div>
              <div class="col-lg-6 col-6 reveal reveal-scale delay-5">
                <div class="small-box text-bg-danger">
                  <div class="inner">
                    <h3><?= $allRecibos ?></h3>
                    <p>Total de recibos</p>
                  </div>
                  <i class="bi bi-receipt small-box-icon"></i>
                  <a href="<?= BASE_PATH . "/recibosTabla" ?>" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Más información <i class="bi bi-link-45deg"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
      <footer class="app-footer">
        <strong>
          Copyright &copy; 2024-2026&nbsp;
          <a href="https://forexfalcon.com" class="text-decoration-none">forexfalcon.com</a>.
        </strong>
      </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"></script>

    <script>
      const elementos = document.querySelectorAll('.reveal');

      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            observer.unobserve(entry.target);
          }
        });
      }, {
        threshold: 0.15,
        rootMargin: '0px 0px -50px 0px'
      });
      
      // Observar cada elemento
      elementos.forEach(elemento => {
        observer.observe(elemento);
      });
    </script>
  </body>
</html>