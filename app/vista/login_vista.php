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

    <meta name="title" content="AdminLTE 4 | Login Page v2" />
    <meta name="author" content="ColorlibHQ" />
    <meta name="description" content="Acceso al panel de administración de Certitransporte. Inicia sesión con tus credenciales para gestionar alumnos, cursos, recibos y matriculados.">
    <meta name="keywords" content="Certitransporte, login, inicio de sesión, panel admin, acceso administrador, gestión de transporte, credenciales">

    <script src="<?= LINKS_PATH . "/js/all.min.js" ?>"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc3/dist/css/adminlte.min.css" crossorigin="anonymous"/>

    <!-- Certitransporte -->
    <link rel="stylesheet" href="<?= LINKS_PATH . "/css/forexfalcon.css" ?>">

  </head>
  <body class="login-page bg-body-secondary">
    <div class="login-box">
      <div class="card card-outline ">
        <div class="card-header">
          <a href="<?= BASE_PATH ?>" class="link-dark text-center link-offset-2 link-opacity-100 link-opacity-50-hover">
            <h1 class="mb-0"><img class="zoom" src="<?= LINKS_PATH . "/imagenes/favicon.png" ?>" alt="Logo forexfalcon" width="100px"></h1>
          </a>
        </div>
        <div class="card-body login-card-body">
          <p class="login-box-msg">Inicia sesión para comenzar tu sesión.</p>

          <form action="<?= BASE_PATH . "/login" ?>" method="post">
            <div class="input-group mb-1">
              <div class="form-floating">
                <input id="loginEmail" type="text" class="form-control <?= isset($errores['usuario']) ? 'is-invalid' : '' ?>" name="usuario" placeholder="" value="<?= htmlspecialchars($usuario) ?>"/>
                <label for="loginEmail" class="red">Usuario</label>
              </div>
              <div class="input-group-text">
                <i class="fa-solid fa-user"></i>
              </div>
            </div>
            <?php if(isset($errores['usuario'])): ?>
              <div class="invalid-feedback d-block mb-2">
                El campo usuario es obligatorio
              </div>
            <?php endif; ?>
            
            <div class="input-group mb-1">
              <div class="form-floating">
                <input id="loginPassword" type="password" class="form-control <?= isset($errores['contraseña']) ? 'is-invalid' : '' ?>" name="contraseña" placeholder=""/>
                <label for="loginPassword">Contraseña</label>
              </div>
              <div class="input-group-text">
                <i class="fa-solid fa-key"></i>
              </div>
            </div>
            <?php if(isset($errores['contraseña'])): ?>
              <div class="invalid-feedback d-block mb-2">
                El campo contraseña es obligatorio
              </div>
            <?php endif; ?>
            
            <?php if(isset($errores['login'])): ?>
              <div class="alert alert-danger mt-2" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                Usuario o contraseña incorrectos
              </div>
            <?php endif; ?>
            
            <button type="submit" class="btn btnEnviarFormulario w-100 my-3">Inicia sesión</button>
          </form>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>

  </body>
</html>
