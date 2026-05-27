<!doctype html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Forexfalcon - Acceso</title>

  <link rel="shortcut icon" href="<?= LINKS_PATH . "/imagenes/favicon.ico" ?>">

  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />

  <meta name="title" content="Forexfalcon | Acceso" />
  <meta name="author" content="Forexfalcon" />
  <meta name="description" content="">
  <meta name="keywords" content="">

  <script src="<?= LINKS_PATH . "/js/all.min.js" ?>" defer></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc3/dist/css/adminlte.min.css" crossorigin="anonymous" />
  
  <!-- ForexFalcon - Login -->
  <script src="<?= LINKS_PATH . "/js/code_js_jquerys/fomularios.js" ?>" defer></script>
  <link rel="stylesheet" href="<?= LINKS_PATH . "/css/login.css" ?>">
  <link rel="stylesheet" href="<?= LINKS_PATH . "/css/forexfalcon.css" ?>">
</head>

<body>

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
            <a id="tab-login-btn" class="nav-link <?= $tipo == "inicio" ? 'active' : '' ?> rounded-0 rounded-top-start"
              data-bs-toggle="tab" href="#tab-login">
              <i class="fa-solid fa-right-to-bracket me-1"></i> Iniciar sesión
            </a>
          </li>
          <li class="nav-item">
            <a id="tab-registro-btn" class="nav-link <?= $tipo == "registro" ? 'active' : '' ?> rounded-0 rounded-top-end"
              data-bs-toggle="tab" href="#tab-registro">
              <i class="fa-solid fa-user-plus me-1"></i> Registrarse
            </a>
          </li>
        </ul>
      </div>

      <div class="card-body login-card-body">
        <div class="tab-content">

          <!-- ── PESTAÑA LOGIN ───────────────────────────────────── -->
          <div class="tab-pane fade <?= $tipo == "inicio" ? 'show active' : '' ?>" id="tab-login">
            <p class="login-box-msg">Inicia sesión para comenzar tu sesión.</p>

            <form action="<?= BASE_PATH . "/inicio_sesion?tipo=inicio" ?>" method="post">
              <!-- Usuario -->
              <div class="input-group mb-1">
                <div class="form-floating">
                  <input id="loginEmail" type="text"
                    class="form-control <?= isset($errores['usuario']) ? 'is-invalid' : '' ?>"
                    name="usuario_gmail" placeholder=""
                    value="<?= htmlspecialchars($usuario ?? '') ?>" required/>
                  <label for="loginEmail" class="red">Usuario o email</label>
                </div>
              </div>

                <div id="errorUsuario" class="invalid-feedback d-block mb-2 d-none">
                  El campo usuario es obligatorio
                </div>


              <!-- Contraseña -->
              <div class="input-group mb-1">
                <div class="form-floating">
                  <input id="loginPassword" type="password"
                    class="form-control <?= isset($errores['contraseña']) ? 'is-invalid' : '' ?>"
                    name="contraseña" placeholder="" />
                  <label for="loginPassword">Contraseña</label>
                </div>
                <div class="input-group-text">
                  <i class="fa-solid fa-eye white"></i>
                </div>
              </div>

                <div id="errorContraseña" class="invalid-feedback d-block mb-2 d-none">
                  El campo contraseña es obligatorio
                </div>


              <!-- Error de credenciales -->
                <div id="errorCredenciales" class="alert alert-danger mt-2 d-none" role="alert">
                  <i class="fa-solid fa-triangle-exclamation me-2"></i>
                  Usuario o contraseña incorrectos
                </div>


              <button type="submit" id="btnInicioSesion" class="btn btnRegistrarse w-100 my-3">
                Iniciar sesión
              </button>
            </form>
          </div>

          <!-- ── PESTAÑA REGISTRO ───────────────────────────────── -->
          <div class="tab-pane fade <?= $tipo == "registro" ? 'show active' : '' ?>" id="tab-registro">
            <p class="login-box-msg">Crea tu cuenta y empieza hoy.</p>

            <form action="<?= BASE_PATH . "/crear_cuenta?tipo=registro" ?>" method="post">
              <!-- Nombre de usuario -->
              <div class="input-group mb-1">
                <div class="form-floating">
                  <input id="regUsuario" type="text"
                    class="form-control <?= isset($erroresReg['usuario']) ? 'is-invalid' : '' ?>"
                    name="usuario" placeholder=""
                    value="<?= htmlspecialchars($regUsuario ?? '') ?>" />
                  <label for="regUsuario">Usuario</label>
                </div>
              </div>

              <div id="errorUsuarioRegistro" class="alert alert-danger mt-2 d-none">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
              </div>

              <!-- Email -->
              <div class="input-group mb-1">
                <div class="form-floating">
                  <input id="regEmail" type="email"
                    class="form-control <?= isset($erroresReg['email']) ? 'is-invalid' : '' ?>"
                    name="email" placeholder=""
                    value="<?= htmlspecialchars($regEmail ?? '') ?>" />
                  <label for="regEmail">Correo electrónico</label>
                </div>
              </div>

              <div id="errorEmailRegistro" class="alert alert-danger mt-2 d-none">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
              </div>

              <!-- Contraseña -->
              <div class="input-group mb-1">
                <div class="form-floating">
                  <input id="regPassword" type="password"
                    class="form-control <?= isset($erroresReg['contraseña']) ? 'is-invalid' : '' ?>"
                    name="contraseña" placeholder="" />
                  <label for="regPassword">Contraseña</label>
                </div>
              </div>

              <div id="errorContraseñaRegistro" class="alert alert-danger mt-2 d-none">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
              </div>

              <!-- Confirmar contraseña -->
              <div class="input-group mb-1">
                <div class="form-floating">
                  <input id="regPasswordConfirm" type="password"
                    class="form-control <?= isset($erroresReg['confirmar_contraseña']) ? 'is-invalid' : '' ?>"
                    name="confirmar_contraseña" placeholder="" />
                  <label for="regPasswordConfirm">Confirmar contraseña</label>
                </div>
                <div class="input-group-text">
                  <i class="fa-solid fa-eye white"></i>
                </div>
              </div>

              <div id="errorConfirmaContraseñaRegistro" class="alert alert-danger mt-2 d-none">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
              </div>

              <button type="submit" id="btnRegistrarse" class="btn btnRegistrarse w-100 my-3">
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