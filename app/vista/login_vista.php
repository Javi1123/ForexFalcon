<!doctype html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Forexfalcon - Acceso</title>
    
    <link rel="shortcut icon" href="<?= LINKS_PATH . "/imagenes/favicon.ico" ?>">
    <link rel="forexfalcon" type="image/x-icon" href="<?= LINKS_PATH . "/imagenes/favicon.ico" ?>">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />

    <meta name="title" content="AdminLTE 4 | Login Page v2" />
    <meta name="author" content="ColorlibHQ" />
    <meta name="description" content="Acceso al panel de administración de Forexfalcon. Inicia sesión con tus credenciales para gestionar alumnos, cursos, recibos y matriculados.">
    <meta name="keywords" content="forexfalcon, login, inicio de sesión, panel admin, acceso administrador, gestión de transporte, credenciales">

    <script src="<?= LINKS_PATH . "/js/all.min.js" ?>"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc3/dist/css/adminlte.min.css" crossorigin="anonymous"/>

    <!-- Forexfalcon -->
    <link rel="stylesheet" href="<?= LINKS_PATH . "/css/forexfalcon.css" ?>">

  </head>
  <body class="login-page bg-body-secondary">
    <div class="login-box">
      <div class="card card-outline">
        <div class="card-header text-center">
          <a href="<?= BASE_PATH ?>">
            <h1 class="mb-0">
              <img class="zoom" src="<?= LINKS_PATH . "/imagenes/logo.png" ?>" alt="Logo forexfalcon" width="100px">
            </h1>
          </a>
        </div>

        <!-- PESTAÑAS -->
        <div class="card-header p-0 border-bottom-0">
          <ul class="nav nav-pills nav-justified">
            <li class="nav-item">
              <a id="tab-login-btn" class="nav-link active rounded-0 rounded-top-start"
                data-bs-toggle="tab" href="#tab-login">
                <i class="fa-solid fa-right-to-bracket me-1"></i> Iniciar sesión
              </a>
            </li>
            <li class="nav-item">
              <a id="tab-registro-btn" class="nav-link rounded-0 rounded-top-end"
                data-bs-toggle="tab" href="#tab-registro">
                <i class="fa-solid fa-user-plus me-1"></i> Registrarse
              </a>
            </li>
          </ul>
        </div>

        <div class="card-body login-card-body">
          <div class="tab-content">

            <!-- ── PESTAÑA LOGIN ───────────────────────────────────── -->
            <div class="tab-pane fade <?= !isset($mostrarRegistro) || !$mostrarRegistro ? 'show active' : '' ?>" id="tab-login">
              <p class="login-box-msg">Inicia sesión para comenzar tu sesión.</p>

              <form action="<?= BASE_PATH . "/login" ?>" method="post">
                <!-- Usuario -->
                <div class="input-group mb-1">
                  <div class="form-floating">
                    <input id="loginEmail" type="text"
                      class="form-control <?= isset($errores['usuario']) ? 'is-invalid' : '' ?>"
                      name="usuario" placeholder=""
                      value="<?= htmlspecialchars($usuario ?? '') ?>"/>
                    <label for="loginEmail" class="red">Usuario</label>
                  </div>
                  <div class="input-group-text">
                    <i class="fa-solid fa-user"></i>
                  </div>
                </div>
                <?php if (isset($errores['usuario'])): ?>
                  <div class="invalid-feedback d-block mb-2">
                    El campo usuario es obligatorio
                  </div>
                <?php endif; ?>

                <!-- Contraseña -->
                <div class="input-group mb-1">
                  <div class="form-floating">
                    <input id="loginPassword" type="password"
                      class="form-control <?= isset($errores['contraseña']) ? 'is-invalid' : '' ?>"
                      name="contraseña" placeholder=""/>
                    <label for="loginPassword">Contraseña</label>
                  </div>
                  <div class="input-group-text">
                    <i class="fa-solid fa-key"></i>
                  </div>
                </div>
                <?php if (isset($errores['contraseña'])): ?>
                  <div class="invalid-feedback d-block mb-2">
                    El campo contraseña es obligatorio
                  </div>
                <?php endif; ?>

                <!-- Error de credenciales -->
                <?php if (isset($errores['login'])): ?>
                  <div class="alert alert-danger mt-2" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                    Usuario o contraseña incorrectos
                  </div>
                <?php endif; ?>

                <button type="submit" class="btn btnEnviarFormulario w-100 my-3">
                  Iniciar sesión
                </button>
              </form>
            </div>

            <!-- ── PESTAÑA REGISTRO ───────────────────────────────── -->
            <div class="tab-pane fade <?= isset($mostrarRegistro) && $mostrarRegistro ? 'show active' : '' ?>" id="tab-registro">
              <p class="login-box-msg">Crea tu cuenta y empieza hoy.</p>

              <form action="<?= BASE_PATH . "/crear_cuenta" ?>" method="post">
                <!-- Nombre de usuario -->
                <div class="input-group mb-1">
                  <div class="form-floating">
                    <input id="regUsuario" type="text"
                      class="form-control <?= isset($erroresReg['usuario']) ? 'is-invalid' : '' ?>"
                      name="usuario" placeholder=""
                      value="<?= htmlspecialchars($regUsuario ?? '') ?>"/>
                    <label for="regUsuario">Usuario</label>
                  </div>
                  <div class="input-group-text">
                    <i class="fa-solid fa-user"></i>
                  </div>
                </div>
                <?php if (isset($erroresReg['usuario'])): ?>
                  <div class="invalid-feedback d-block mb-2">
                    <?= htmlspecialchars($erroresReg['usuario']) ?>
                  </div>
                <?php endif; ?>

                <!-- Email -->
                <div class="input-group mb-1">
                  <div class="form-floating">
                    <input id="regEmail" type="email"
                      class="form-control <?= isset($erroresReg['email']) ? 'is-invalid' : '' ?>"
                      name="email" placeholder=""
                      value="<?= htmlspecialchars($regEmail ?? '') ?>"/>
                    <label for="regEmail">Correo electrónico</label>
                  </div>
                  <div class="input-group-text">
                    <i class="fa-solid fa-envelope"></i>
                  </div>
                </div>
                <?php if (isset($erroresReg['email'])): ?>
                  <div class="invalid-feedback d-block mb-2">
                    <?= htmlspecialchars($erroresReg['email']) ?>
                  </div>
                <?php endif; ?>

                <!-- Contraseña -->
                <div class="input-group mb-1">
                  <div class="form-floating">
                    <input id="regPassword" type="password"
                      class="form-control <?= isset($erroresReg['contraseña']) ? 'is-invalid' : '' ?>"
                      name="contraseña" placeholder=""/>
                    <label for="regPassword">Contraseña</label>
                  </div>
                  <div class="input-group-text">
                    <i class="fa-solid fa-key"></i>
                  </div>
                </div>
                <?php if (isset($erroresReg['contraseña'])): ?>
                  <div class="invalid-feedback d-block mb-2">
                    <?= htmlspecialchars($erroresReg['contraseña']) ?>
                  </div>
                <?php endif; ?>

                <!-- Confirmar contraseña -->
                <div class="input-group mb-1">
                  <div class="form-floating">
                    <input id="regPasswordConfirm" type="password"
                      class="form-control <?= isset($erroresReg['confirmar_contraseña']) ? 'is-invalid' : '' ?>"
                      name="confirmar_contraseña" placeholder=""/>
                    <label for="regPasswordConfirm">Confirmar contraseña</label>
                  </div>
                  <div class="input-group-text">
                    <i class="fa-solid fa-lock"></i>
                  </div>
                </div>
                <?php if (isset($erroresReg['confirmar_contraseña'])): ?>
                  <div class="invalid-feedback d-block mb-2">
                    <?= htmlspecialchars($erroresReg['confirmar_contraseña']) ?>
                  </div>
                <?php endif; ?>

                <!-- Error general de registro -->
                <?php if (isset($erroresReg['registro'])): ?>
                  <div class="alert alert-danger mt-2" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                    <?= htmlspecialchars($erroresReg['registro']) ?>
                  </div>
                <?php endif; ?>

                <button type="submit" class="btn btnEnviarFormulario w-100 my-3">
                  Crear cuenta
                </button>
              </form>
            </div>

          </div><!-- /tab-content -->
        </div><!-- /card-body -->
      </div><!-- /card -->
    </div><!-- /login-box -->

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
  </body>
</html>
