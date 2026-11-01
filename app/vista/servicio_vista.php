<?php

/* Página de un servicio contratado: misma estructura que recursos_vista.php */
$DESCRIPCION = "forexfalcon.com";
$CLAVES = "servicios, vídeos";
$AUTOR = "ForexFalcon";
$TITULO = $servicio['Nombre_servicio'] . " - ForexFalcon";
$ACTIVO = 11;

// Empezamos
echo '<!DOCTYPE html><html lang="es-es" dir="ltr">';
    include_once("recursos/cabecera.php");                                               // <head>...</head>  (carga servicio.css porque $vistaServicio está activa)
    echo '<body class="layout-fixed sidebar-expand-lg bg-body-tertiary p-0">';
    echo '<div class="app-wrapper">';
        include_once("recursos/menu.php");                                               // Menú superior fijo + barra lateral (marca este servicio como activo)
        include_once("servicio/main_servicio.php");                                      // Bloque central: reproductor / aviso según el tipo
        include_once("recursos/chat_burbuja.php");                                       // Burbuja de mensajería
        include_once("recursos/pie.php");                                                // pie de página
    echo '</div>';
    ?>
    <script src="<?= LINKS_PATH . "/js/code_js_jquerys/servicio.js?v=" . (@filemtime(__DIR__ . '/../../public/js/code_js_jquerys/servicio.js') ?: 1) ?>" defer></script>
    <?php
    echo '</body>';
echo '</html>';
?>
