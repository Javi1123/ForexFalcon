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

  <script src="<?= LINKS_PATH . "/js/code_js_jquerys/fomularios.js?v=" . @filemtime(dirname(__DIR__, 2) . "/public/js/code_js_jquerys/fomularios.js") ?>" defer></script>
  <link rel="stylesheet" href="<?= LINKS_PATH . "/css/login.css?v=" . @filemtime(dirname(__DIR__, 2) . "/public/css/login.css") ?>">
  
</head>

<body>

  <div class="gradient-mesh">
    <span></span>
    <span></span>
    <span></span>
    <span></span>
  </div>

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
          <p class="login-box-msg">Sigue el mercado donde lo dejaste.</p>

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
                <i id="eye-contraseña-login" class="fa-solid fa-eye"></i>
              </div>
            </div>

            <div id="errorContraseña" class="invalid-feedback d-block mb-2 <?= empty($errorLogin) ? 'd-none' : '' ?>"><?= htmlspecialchars($errorLogin) ?></div>
            <a href="<?= BASE_PATH . '/recuperar' ?>" class="ff-olvido">¿Has olvidado tu contraseña?</a>
            
            <button type="submit" id="btnInicioSesion" class="btn btnInicioSesion w-100 my-3">
              <span>Iniciar sesión</span>
            </button>
          </form>

          <div class="ff-separador"><span>o</span></div>

          <a href="<?= BASE_PATH . '/google/inicio' ?>" class="ff-google" id="btnGoogle">
            <svg class="ff-google-g" viewBox="0 0 48 48" width="20" height="20" aria-hidden="true">
              <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
              <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
              <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
              <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
            </svg>
            <span>Continuar con Google</span>
          </a>
        </div>

        <!-- ── Panel: Registro ──────────────────────────────── -->
        <div id="ff-panel-registro" class="ff-panel <?= $tipo != 'registro' ? 'd-none' : '' ?>">

          <!-- Indicador de pasos -->
          <div class="ff-steps-indicator">
            <span class="ff-step-dot active" data-step="1"></span>
            <span class="ff-step-line"></span>
            <span class="ff-step-dot" data-step="2"></span>
            <span class="ff-step-line"></span>
            <span class="ff-step-dot" data-step="3"></span>
          </div>

          <form id="formRegistro" action="<?= BASE_PATH . "/acciones?tipo=registro" ?>" method="post">

            <!-- ═══ PASO 1 — Quién eres ═══ -->
            <div class="ff-step" data-step="1">
              <p class="login-box-msg">Cuéntanos quién eres.</p>

              <div class="input-group mb-1">
                <div class="form-floating">
                  <input id="regNombre" type="text"
                    class="form-control <?= isset($erroresReg['nombre']) ? 'is-invalid' : '' ?>"
                    name="nombre_registro" placeholder=""
                    value="<?= htmlspecialchars($regNombre ?? '') ?>" required/>
                  <label for="regNombre">Nombre</label>
                </div>
              </div>
              <div id="errorNombreRegistro" class="invalid-feedback d-block mb-2 d-none"></div>

              <div class="input-group mb-1">
                <div class="form-floating">
                  <input id="regApellido" type="text"
                    class="form-control <?= isset($erroresReg['apellido']) ? 'is-invalid' : '' ?>"
                    name="apellido_registro" placeholder=""
                    value="<?= htmlspecialchars($regApellido ?? '') ?>" required/>
                  <label for="regApellido">Apellido</label>
                </div>
              </div>
              <div id="errorApellidoRegistro" class="invalid-feedback d-block mb-2 d-none"></div>

              <div class="input-group mb-1">
                <div class="form-floating">
                  <input id="regDate" type="date"
                    class="form-control <?= isset($erroresReg['fecha_nacimiento']) ? 'is-invalid' : '' ?>"
                    name="fecha_nacimiento_registro" placeholder=""
                    value="<?= htmlspecialchars($regFecha ?? '') ?>" min="<?= $minFecha ?>" max="<?= $maxFecha ?>" required/>
                  <label for="regDate">Fecha de nacimiento</label>
                </div>
              </div>
              <div id="errorFechaRegistro" class="invalid-feedback d-block mb-2 d-none"></div>

              <button type="button" class="btn btnInicioSesion w-100 my-3 ff-next" data-next="2">
                <span>Siguiente</span>
              </button>
            </div>

            <!-- ═══ PASO 2 — Cómo contactarte ═══ -->
            <div class="ff-step d-none" data-step="2">
              <p class="login-box-msg">¿Cómo te contactamos?</p>

              <div class="input-group mb-1">
                <div class="form-floating">
                  <input id="regEmail" type="email"
                    class="form-control <?= isset($erroresReg['email']) ? 'is-invalid' : '' ?>"
                    name="email_registro" placeholder=""
                    value="<?= htmlspecialchars($regEmail ?? '') ?>" required/>
                  <label for="regEmail">Correo electrónico</label>
                </div>
              </div>
              <div id="errorEmailRegistro" class="invalid-feedback d-block mb-2 d-none"></div>

              <div class="input-group mb-1">
                <select id="regTelPrefijo" class="form-select ff-tel-prefijo" aria-label="Prefijo telefónico">
                  <!-- Se rellena por JS -->
                </select>
                <div class="form-floating">
                  <input id="regTelefono" type="tel"
                    class="form-control <?= isset($erroresReg['telefono']) ? 'is-invalid' : '' ?>"
                    name="telefono_registro" placeholder=""
                    value="<?= htmlspecialchars($regTelefono ?? '') ?>" required/>
                  <label for="regTelefono">Teléfono</label>
                </div>
              </div>
              <div id="errorTelefonoRegistro" class="invalid-feedback d-block mb-2 d-none"></div>

              <!-- Después -->
              <div class="input-group mb-1">
                <div class="form-floating">
                  <select id="regPais" name="pais_registro" class="form-select <?= isset($erroresReg['pais']) ? 'is-invalid' : '' ?>" required>
                    <option value="" disabled <?= empty($regPais) ? 'selected' : '' ?>>Selecciona un país</option>
                    <?php foreach ($paises as $pais): ?>
                      <option value="<?= htmlspecialchars($pais) ?>" <?= (isset($regPais) && $regPais === $pais) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($pais) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <label for="regPais">País</label>
                </div>
              </div>
              <div id="errorPaisRegistro" class="invalid-feedback d-block mb-2 d-none"></div>

              <div class="d-flex gap-2 my-3">
                <button type="button" class="btn ff-back w-50" data-back="1">
                  <span>Atrás</span>
                </button>
                <button type="button" class="btn btnInicioSesion w-50 ff-next" data-next="3">
                  <span>Siguiente</span>
                </button>
              </div>
            </div>

            <!-- ═══ PASO 3 — Seguridad ═══ -->
            <div class="ff-step d-none" data-step="3">
              <p class="login-box-msg">Crea tu contraseña.</p>

              <div class="input-group mb-1">
                <div class="form-floating">
                  <input id="regPassword" type="password"
                    class="form-control <?= isset($erroresReg['contraseña']) ? 'is-invalid' : '' ?>"
                    name="contraseña_registro" placeholder="" required/>
                  <label for="regPassword">Contraseña</label>
                </div>
                <div id="eye-contraseña-registro" class="input-group-text">
                  <i id="eye-icon-registro" class="fa-solid fa-eye"></i>
                </div>
              </div>
              <div id="errorContraseñaRegistro" class="invalid-feedback d-block mb-2 d-none"></div>

              <div class="input-group mb-1">
                <div class="form-floating">
                  <input id="regPasswordConfirm" type="password"
                    class="form-control <?= isset($erroresReg['confirmar_contraseña']) ? 'is-invalid' : '' ?>"
                    name="confirmar_contraseña_registro" placeholder="" required/>
                  <label for="regPasswordConfirm">Confirmar contraseña</label>
                </div>
                <div id="eye-confirmar-contraseña" class="input-group-text">
                  <i id="eye-contraseña-registrarse" class="fa-solid fa-eye"></i>
                </div>
              </div>
              <div id="errorConfirmaContraseñaRegistro" class="invalid-feedback d-block mb-2 d-none"></div>

              <!-- Requisitos que aún le faltan a la contraseña (se rellena en fomularios.js) -->
              <div id="reglasContrasena" class="ff-reglas d-none" role="status" aria-live="polite">
                <p class="ff-reglas-titulo" id="reglasTitulo"></p>
                <ul class="ff-reglas-lista" id="reglasLista"></ul>
              </div>

              <div class="d-flex gap-2 my-3">
                <button type="button" class="btn ff-back w-50" data-back="2">
                  <span>Atrás</span>
                </button>
                <button type="submit" id="btnRegistrarse" class="btn btnInicioSesion w-50">
                  <span>Crear cuenta</span>
                </button>
              </div>
            </div>

          </form>
        </div>

      </div><!-- /card-body -->
    </div><!-- /card -->
  </div><!-- /login-box -->

  <?php if (isset($_SESSION['toast'])): 
      $toast = $_SESSION['toast'];
      unset($_SESSION['toast']); // Se elimina para que no vuelva a aparecer al refrescar
  ?>

  <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
    <div id="registroToast" class="toast align-items-center text-white <?= $toast['tipo'] === 'error' ? 'bg-danger' : 'bg-success' ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body d-flex align-items-start">
          <i class="fa-solid <?= htmlspecialchars($toast['icono'] ?? ($toast['tipo'] === 'error' ? 'fa-circle-exclamation' : 'fa-circle-check')) ?> me-2 mt-1"></i>
          <span><?php if (!empty($toast['titulo'])): ?><strong class="d-block"><?= htmlspecialchars($toast['titulo']) ?></strong><?php endif; ?><?= htmlspecialchars($toast['mensaje']) ?></span>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const toastEl = document.getElementById('registroToast');
      if (toastEl) {
        const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
        toast.show();
      }
    });
  </script>

  <?php endif; ?>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>
</html>