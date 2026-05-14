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
    <meta name="description" content="Administra el catálogo de cursos de Certitransporte. Gestiona claves, descripciones, precios mensuales, matriculas, altas/bajas y notas de cada curso.">
    <meta name="keywords" content="Certitransporte, cursos, gestión de cursos, catálogo de cursos, precios, matrículas, altas y bajas, panel admin, formación">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous"/>

    <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc3/dist/js/adminlte.min.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc3/dist/css/adminlte.min.css" crossorigin="anonymous"/>
    
    <!-- Certitransporte -->
    <link rel="stylesheet" href="<?= LINKS_PATH . "/css/forexfalcon.css" ?>">
    <script src="<?= LINKS_PATH . "/js/code_js_jquerys/accionesTablaCurso.js" ?>" defer></script>
    
    <!-- Jquery -->
    <script src="<?= LINKS_PATH . "/js/jquery-4.0.0.min.js" ?>"></script>
    
    <!-- DataTable -->
    <script src="https://cdn.datatables.net/2.3.7/js/dataTables.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.7/css/dataTables.dataTables.css">

    <!-- Responsive DataTables  -->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.dataTables.min.css">
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="<?= LINKS_PATH . "/js/sweetalert2.all.min.js" ?>"></script>
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
                <h3 class="mb-0">Tabla de cursos</h3>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="<?= BASE_PATH . "/admin" ?>">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Tabla de cursos</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="app-content">
          <div class="container-fluid">

            <div class="row">
              <div class="card mb-4">
                <div class="card-header">
                  <h3 class="card-title">Cursos</h3>
                  <div class="card-tools">
                    <button type="button" id="btnCrearCurso" class="btn btn-tool btn-sm" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-custom-class="crear-tooltip" data-bs-html="true" title="<b>Crear nuevo curso</b>">
                      <i class="bi bi-plus-circle-fill green"></i>
                    </button>
                  </div>
                </div>
                <div class="table-responsive">
                  <table id="tablaCursos" class="table table-striped align-middle">
                    <thead>
                      <tr>
                        <th>Clave</th>
                        <th>Descripción</th>
                        <th>Precio del mes</th>
                        <th>Matricula</th>
                        <th>Baja</th>
                        <th>Notas</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach($cursos as $cur) : ?>
                        <tr id="<?= $cur["claveCurso"] ?>">
                          <td class="clave"><?= $cur["claveCurso"] ?></td>
                          <td class="descripcion"><?= $cur["descripcion"] ?></td>
                          <td class="precioMes"><?= $cur["precioMes"] ?></td>
                          <td class="matricula"><?= $cur["matricula"] ?></td>
                          <td class="baja"><?= $cur["baja"] ?></td>
                          <td class="notas"><?= $cur["notas"] ?></td>
                          <td class="text-center">
                              <button type="button" id="<?= $cur["claveCurso"] ?>" class="btn btn-tool btnEditarCurso" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="editar-tooltip" data-bs-html="true" title="<b>Editar curso</b>"><i id="<?= $cur["claveCurso"] ?>" class="bi bi-pen-fill orange"></i></button>
                            <?php if($cur["baja"] == "Si") : ?>
                              <button type="button" id="<?= $cur["claveCurso"] ?>" class="btn btn-tool btnDarAlta" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="crear-tooltip" data-bs-html="true" title="<b>Dar de alta</b>"><i id="<?= $cur["claveCurso"] ?>" class="bi bi-plus-circle-fill green"></i></button>
                            <?php else : ?>
                              <button type="button" id="<?= $cur["claveCurso"] ?>" class="btn btn-tool btnDarBaja" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="baja-tooltip" data-bs-html="true" title="<b>Dar de baja</b>"><i id="<?= $cur["claveCurso"] ?>" class="bi bi-dash-circle-fill red"></i></button>
                            <?php endif; ?>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
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
    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
        // Inicializar todos los tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl);
        });
      });
    </script>

    <?php if(isset($_SESSION["popErrores"])) : ?>
      <script>
        const popErrores = () =>{
          Swal.fire({
            icon: "<?= $_SESSION["popErrores"]["icon"] ?>",
            title: "<?= $_SESSION["popErrores"]["titulo"] ?>",
            text: "<?= $_SESSION["popErrores"]["texto"] ?>",
          });
        }

        popErrores();
      </script>
    <?php 
      unset($_SESSION["popErrores"]);
      endif;
    ?>
  </body>
</html>
