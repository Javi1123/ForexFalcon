<?php
/**
 * Pantallas de "He olvidado mi contraseña". Variables: $modo ('solicitar' | 'nueva' | 'invalido'),
 * y para 'nueva': $errorReset y $csrf.
 */
$ver = fn(string $ruta) => LINKS_PATH . $ruta . '?v=' . @filemtime(dirname(__DIR__, 2) . '/public' . $ruta);
?>
<!doctype html>
<html lang="es">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Forexfalcon - Restablecer contraseña</title>

  <link rel="shortcut icon" href="<?= LINKS_PATH . "/imagenes/favicon.ico" ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
  <meta name="robots" content="noindex, nofollow" />
  <meta name="referrer" content="no-referrer" />

  <script src="<?= LINKS_PATH . "/js/all.min.js" ?>" defer></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc3/dist/css/adminlte.min.css" crossorigin="anonymous" />
  <link rel="stylesheet" href="<?= $ver('/css/login.css') ?>">
  <?php if ($modo === 'nueva'): ?>
    <script src="<?= $ver('/js/code_js_jquerys/restablecer.js') ?>" defer></script>
  <?php endif; ?>
</head>

<body>

  <div class="gradient-mesh">
    <span></span><span></span><span></span><span></span>
  </div>

  <div class="login-box">
    <div class="card card-outline">

      <div class="card-header text-center">
        <a href="<?= BASE_PATH ?>">
          <h1 class="mb-0">
            <img class="zoom" src="<?= LINKS_PATH . "/imagenes/logo.png" ?>" alt="Logo forexfalcon" width="100px">
          </h1>
        </a>
      </div>

      <div class="card-body login-card-body">

      <?php if ($modo === 'solicitar'): ?>

        <div class="ff-reset-icono"><i class="fa-solid fa-envelope-circle-check"></i></div>
        <p class="login-box-msg ff-reset-titulo">¿Has olvidado tu contraseña?</p>
        <p class="ff-reset-texto">Escribe el correo de tu cuenta y te enviaremos un enlace para crear una nueva. Solo funciona si lo abres desde ese correo.</p>

        <form action="<?= BASE_PATH . '/recuperar' ?>" method="post" id="formRecuperar">
          <div class="input-group mb-1">
            <div class="form-floating">
              <input id="recEmail" type="email" class="form-control" name="email_recuperar" placeholder="" maxlength="100" required autofocus />
              <label for="recEmail">Correo electrónico</label>
            </div>
          </div>

          <button type="submit" id="btnRecuperar" class="btn btnInicioSesion w-100 my-3">
            <span>Enviar enlace</span>
          </button>
        </form>

        <a href="<?= BASE_PATH . '/acciones?tipo=inicio' ?>" class="ff-reset-volver"><i class="fa-solid fa-arrow-left"></i> Volver a iniciar sesión</a>

      <?php elseif ($modo === 'nueva'): ?>

        <div class="ff-reset-icono"><i class="fa-solid fa-key"></i></div>
        <p class="login-box-msg ff-reset-titulo">Crea tu nueva contraseña</p>

        <form action="<?= BASE_PATH . '/restablecer' ?>" method="post" id="formRestablecer" novalidate>
          <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">

          <div class="input-group mb-1">
            <div class="form-floating">
              <input id="regPassword" type="password" class="form-control" name="contraseña_nueva" placeholder="" autocomplete="new-password" required autofocus />
              <label for="regPassword">Nueva contraseña</label>
            </div>
            <div class="input-group-text ff-ojo" data-ojo="regPassword" role="button" tabindex="0" aria-label="Mostrar u ocultar contraseña"><i class="fa-solid fa-eye"></i></div>
          </div>

          <div class="input-group mb-1">
            <div class="form-floating">
              <input id="regPasswordConfirm" type="password" class="form-control" name="confirmar_contraseña_nueva" placeholder="" autocomplete="new-password" required />
              <label for="regPasswordConfirm">Confirmar contraseña</label>
            </div>
            <div class="input-group-text ff-ojo" data-ojo="regPasswordConfirm" role="button" tabindex="0" aria-label="Mostrar u ocultar contraseña"><i class="fa-solid fa-eye"></i></div>
          </div>

          <?php if (!empty($errorReset)): ?>
            <div class="invalid-feedback d-block mb-2" role="alert"><?= htmlspecialchars($errorReset) ?></div>
          <?php endif; ?>

          <div id="reglasContrasena" class="ff-reglas d-none" role="status" aria-live="polite">
            <p class="ff-reglas-titulo" id="reglasTitulo"></p>
            <ul class="ff-reglas-lista" id="reglasLista"></ul>
          </div>

          <button type="submit" id="btnRestablecer" class="btn btnInicioSesion w-100 my-3">
            <span>Guardar contraseña</span>
          </button>
        </form>

      <?php else: /* invalido */ ?>

        <div class="ff-reset-icono ff-reset-icono-error"><i class="fa-solid fa-link-slash"></i></div>
        <p class="login-box-msg ff-reset-titulo">Enlace no válido</p>
        <p class="ff-reset-texto">Este enlace ha caducado, ya se ha usado o no es correcto. Para cambiar tu contraseña debes abrir el enlace que te enviamos por correo; si no lo encuentras, pide uno nuevo.</p>

        <a href="<?= BASE_PATH . '/recuperar' ?>" class="btn btnInicioSesion w-100 my-3"><span>Pedir un enlace nuevo</span></a>
        <a href="<?= BASE_PATH . '/acciones?tipo=inicio' ?>" class="ff-reset-volver"><i class="fa-solid fa-arrow-left"></i> Volver a iniciar sesión</a>

      <?php endif; ?>

      </div>
    </div>
  </div>

  <?php if (isset($_SESSION['toast'])):
    $toast = $_SESSION['toast'];
    unset($_SESSION['toast']);
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
      const el = document.getElementById('registroToast');
      if (el) new bootstrap.Toast(el, { delay: 3000 }).show();
    });
  </script>
  <?php endif; ?>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script>
    // Evita doble envío del formulario de solicitud
    document.getElementById('formRecuperar')?.addEventListener('submit', function () {
      const b = document.getElementById('btnRecuperar');
      b.disabled = true; b.querySelector('span').textContent = 'Enviando...';
    });
  </script>
</body>
</html>
