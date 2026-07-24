<!doctype html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Forexfalcon - Acceso</title>

  <link rel="shortcut icon" href="<?= LINKS_PATH . "/imagenes/favicon.ico" ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
  <meta name="title" content="Forexfalcon | Acceso" />
  <meta name="author" content="Forexfalcon" />

  <script src="<?= LINKS_PATH . "/js/all.min.js" ?>" defer></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc3/dist/css/adminlte.min.css" crossorigin="anonymous" />

  <script src="<?= LINKS_PATH . "/js/code_js_jquerys/fomularios.js" ?>" defer></script>
  <link rel="stylesheet" href="<?= LINKS_PATH . "/css/login.css" ?>">
  <link rel="stylesheet" href="<?= LINKS_PATH . "/css/forexfalcon.css" ?>">
</head>

<body>

  <div class="login-box">
    <div class="card card-outline">

      <!-- Logo -->
      <div class="card-header text-center">
        <a href="<?= BASE_PATH ?>">
          <h1 class="mb-0">
            <img class="zoom" src="<?= LINKS_PATH . "/imagenes/logo.png" ?>" alt="Logo forexfalcon" width="100px">
          </h1>
        </a>
      </div>

      <!-- ── Tabs nav ──────────────────────────────────────── -->
      <div class="card-header p-0 border-bottom-0">
        <div class="ff-tabs-nav">
          <button
            class="ff-tab-btn <?= $tipo == 'inicio'   ? 'ff-active' : '' ?>"
            data-ff-target="ff-panel-login">
            <i class="fa-solid fa-right-to-bracket"></i> Iniciar sesión
          </button>
          <button
            class="ff-tab-btn <?= $tipo == 'registro' ? 'ff-active' : '' ?>"
            data-ff-target="ff-panel-registro">
            <i class="fa-solid fa-user-plus"></i> Registrarse
          </button>
        </div>
      </div>

      <div class="card-body login-card-body">

        <!-- ── Panel: Login ─────────────────────────────────── -->
        <div id="ff-panel-login" class="ff-panel <?= $tipo != 'inicio' ? 'd-none' : '' ?>">
          <p class="login-box-msg">Inicia sesión para comenzar tu sesión.</p>

          <form action="<?= BASE_PATH . "/acciones?tipo=inicio" ?>" method="post">

            <div class="input-group mb-1">
              <div class="form-floating">
                <input id="loginEmail" type="email"
                  class="form-control <?= isset($errores['email']) ? 'is-invalid' : '' ?>"
                  name="email_login" placeholder=""
                  value="<?= htmlspecialchars($email ?? '') ?>"/>
                <label for="loginEmail">Email</label>
              </div>
            </div>
            <div id="errorEmail" class="invalid-feedback d-block mb-2 d-none"></div>

            <div class="input-group mb-1">
              <div class="form-floating">
                <input id="loginPassword" type="password"
                  class="form-control <?= isset($errores['contraseña']) ? 'is-invalid' : '' ?>"
                  name="contraseña_login" placeholder="" />
                <label for="loginPassword">Contraseña</label>
              </div>
              <div id="eye-contraseña" class="input-group-text">
                <i id="eye-contraseña-login" class="fa-solid fa-eye white"></i>
              </div>
            </div>

            <div id="errorContraseña" class="invalid-feedback d-block mb-2 <?= empty($errorLogin) ? 'd-none' : '' ?>"><?= htmlspecialchars($errorLogin) ?></div>
            
            <button type="submit" id="btnInicioSesion" class="btn btnInicioSesion w-100 my-3">
              Iniciar sesión
            </button>
          </form>
        </div>

        <!-- ── Panel: Registro ──────────────────────────────── -->
        <div id="ff-panel-registro" class="ff-panel <?= $tipo != 'registro' ? 'd-none' : '' ?>">
          <p class="login-box-msg">Crea tu cuenta y empieza hoy.</p>

          <form action="<?= BASE_PATH . "/acciones?tipo=registro" ?>" method="post">

            <!-- Nombre -->
            <div class="input-group mb-1">
              <div class="form-floating">
                <input id="regNombre" type="nombre"
                  class="form-control <?= isset($erroresReg['nombre']) ? 'is-invalid' : '' ?>"
                  name="nombre_registro" placeholder=""
                  value="<?= htmlspecialchars($regNombre ?? '') ?>" />
                <label for="regNombre">Nombre</label>
              </div>
            </div>
            <div id="errorNombreRegistro" class="invalid-feedback d-block mb-2 d-none"></div>
            
            <!-- Apellido -->
            <div class="input-group mb-1">
              <div class="form-floating">
                <input id="regApellido" type="apellido"
                  class="form-control <?= isset($erroresReg['apellido']) ? 'is-invalid' : '' ?>"
                  name="apellido_registro" placeholder=""
                  value="<?= htmlspecialchars($regApellido ?? '') ?>" />
                <label for="regApellido">Apellido</label>
              </div>
            </div>
            <div id="errorApellidoRegistro" class="invalid-feedback d-block mb-2 d-none"></div>
            
            <!-- Email -->
            <div class="input-group mb-1">
              <div class="form-floating">
                <input id="regEmail" type="email"
                  class="form-control <?= isset($erroresReg['email']) ? 'is-invalid' : '' ?>"
                  name="email_registro" placeholder=""
                  value="<?= htmlspecialchars($regEmail ?? '') ?>" />
                <label for="regEmail">Correo electrónico</label>
              </div>
            </div>
            <div id="errorEmailRegistro" class="invalid-feedback d-block mb-2 d-none"></div>

            <!-- Teléfono -->
            <div class="input-group mb-1">
              <div class="form-floating">
                <input id="regTelefono" type="tel"
                  class="form-control <?= isset($erroresReg['telefono']) ? 'is-invalid' : '' ?>"
                  name="telefono_registro" placeholder=""
                  value="<?= htmlspecialchars($regTelefono ?? '') ?>" />
                <label for="regTelefono">Teléfono</label>
              </div>
            </div>
            <div id="errorTelefonoRegistro" class="invalid-feedback d-block mb-2 d-none"></div>

            <!-- País -->
            <div class="form-floating mb-1">
              <select id="regPais" name="pais_registro"
                class="form-select <?= isset($erroresReg['pais']) ? 'is-invalid' : '' ?>"
                style="height: 58px; padding-top: 1.625rem; padding-bottom: .625rem;">
                <option value="" disabled <?= empty($regPais) ? 'selected' : '' ?>>Selecciona un país</option>
                <?php foreach ($paises as $pais): ?>
                  <option value="<?= htmlspecialchars($pais) ?>"
                    <?= (isset($regPais) && $regPais === $pais) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($pais) ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <label for="regPais">País</label>
            </div>
            <div id="errorPaisRegistro" class="invalid-feedback d-block mb-2 d-none"></div>

            <!-- Fecha de nacimiento -->
            <div class="input-group mb-1">
              <div class="form-floating">
                <input id="regDate" type="date"
                  class="form-control <?= isset($erroresReg['fecha_nacimiento']) ? 'is-invalid' : '' ?>"
                  name="fecha_nacimiento_registro" placeholder=""
                  value="<?= htmlspecialchars($regFecha ?? '') ?>" min="<?= $minFecha ?>" max="<?=  $maxFecha ?>" />
                <label for="regDate">Fecha de nacimiento</label>
              </div>
            </div>
            <div id="errorFechaRegistro" class="invalid-feedback d-block mb-2 d-none"></div>

            <!-- Contraseña -->
            <div class="input-group mb-1">
              <div class="form-floating">
                <input id="regPassword" type="password"
                  class="form-control <?= isset($erroresReg['contraseña']) ? 'is-invalid' : '' ?>"
                  name="contraseña_registro" placeholder="" />
                <label for="regPassword">Contraseña</label>
              </div>
            </div>
            <div id="errorContraseñaRegistro" class="invalid-feedback d-block mb-2 d-none"></div>

            <!-- Confirmar contraseña -->
            <div class="input-group mb-1">
              <div class="form-floating">
                <input id="regPasswordConfirm" type="password"
                  class="form-control <?= isset($erroresReg['confirmar_contraseña']) ? 'is-invalid' : '' ?>"
                  name="confirmar_contraseña_registro" placeholder="" />
                <label for="regPasswordConfirm">Confirmar contraseña</label>
              </div>
              <div id="eye-confirmar-contraseña" class="input-group-text">
                <i id="eye-contraseña-registrarse" class="fa-solid fa-eye white"></i>
              </div>
            </div>
            <div id="errorConfirmaContraseñaRegistro" class="invalid-feedback d-block mb-2 d-none"></div>

            <button type="submit" id="btnRegistrarse" class="btn btnRegistrarse w-100 my-3">
              Crear cuenta
            </button>
          </form>
        </div>

      </div><!-- /card-body -->
    </div><!-- /card -->
  </div><!-- /login-box -->

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>

</body>
</html>