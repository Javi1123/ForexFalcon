<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="robots" content="index, follow">
  <meta name="description" content="<?php echo $DESCRIPCION; ?>">
  <meta name="keywords" content="<?php echo $CLAVES; ?>">
  <meta name="author" content="<?php echo $AUTOR; ?>">
  <title><?php echo $TITULO; ?></title>
  <link rel="shortcut icon" href="<?= LINKS_PATH . "/imagenes/favicon.ico" ?>">

  <!-- Bootstrap CSS -->
  <link href="<?= LINKS_PATH . "/css/bootstrap.min.css" ?>" rel="stylesheet">
  <script src="<?= LINKS_PATH . "/js/bootstrap.bundle.min.js" ?>"></script>

  <!-- Font Awesome Icon -->
  <script src="<?= LINKS_PATH . "/js/all.min.js" ?>"></script>
  
  <!-- Jquery -->
  <script src="<?= LINKS_PATH . "/js/jquery-4.0.0.min.js" ?>"></script>
  
  <!-- SweetAlert2 -->
  <script src="<?= LINKS_PATH . "/js/sweetalert2.all.min.js" ?>"></script>

  <!-- TinyMCE -->
  <script src="<?= LINKS_PATH . "/js/tinymce/tinymce.min.js" ?>"></script>

  <!-- Css y JS Certitransporte-->
  <link rel="stylesheet" href="<?= LINKS_PATH . "/css/forexfalcon.css" ?>">
  <link rel="stylesheet" href="<?= LINKS_PATH . "/css/menuForexFalcon.css" ?>">
  <script src="<?= LINKS_PATH . "/js/code_js_jquerys/gestion_de_nav.js" ?>" defer></script>
  <script src="<?= LINKS_PATH . "/js/code_js_jquerys/forexfalcon.js" ?>" defer></script>

</head>

<div id="subir"></div>

<!-- Mostar toast -->
<div aria-live="polite p-3" aria-atomic="true" class="bg-body-secondary position-relative bd-example-toasts rounded-3">
  <div class="toast-container p-3 position-fixed bottom-0 end-0" id="toastPlacement">
    <div class="toast fade w-100" id="toastCookies" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false" style="max-width: 600px;z-index: 2000;">
      <div class="toast-header orange-background">
        <span class="me-auto text-white fw-bold"><i class="fa-solid fa-cookie me-2"></i> Bienvenidos !!!</span>
        <button type="button" id="btnCookies" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body black-background white" >
        Este <strong class="red">portal web</strong> únicamente utiliza cookies propias con finalidad técnica, no cede datos de carácter personal de los usuarios sin su conocimiento.
        <p class="mb-0 small fst-italic orange">Sin embargo, contiene enlaces a sitios web de terceros con políticas de privacidad ajenas a este portal web que usted podrá decidir si acepta o no cuando acceda a ellos.</p>
      </div>
    </div>
  </div>
</div>