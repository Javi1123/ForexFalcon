<?php

/* Vídeos de un servicio (administración): misma estructura que admin_servicios_vista.php */
$DESCRIPCION = "forexfalcon.com";
$CLAVES = "administración, vídeos";
$AUTOR = "ForexFalcon";
$TITULO = "Vídeos de " . $servicio['Nombre_servicio'] . " - ForexFalcon";
$ACTIVO = 12;

// Empezamos
echo '<!DOCTYPE html><html lang="es-es" dir="ltr">';
    include_once("recursos/cabecera.php");                                               // <head>...</head>  (carga admin-servicios.css porque $vistaAdmin está activa)
    echo '<body class="layout-fixed sidebar-expand-lg bg-body-tertiary p-0">';
    echo '<div class="app-wrapper">';
        include_once("recursos/menu.php");                                               // Menú superior fijo + barra lateral
        include_once("admin/main_admin_videos.php");                                     // Bloque central: subida + lista
        include_once("recursos/chat_burbuja.php");                                       // Burbuja de mensajería
        include_once("recursos/pie.php");                                                // pie de página
    echo '</div>';
    ?>
    <script>
      window.FF_VIDEOS = <?= json_encode($config, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    </script>
    <script src="<?= LINKS_PATH . "/js/code_js_jquerys/admin-videos.js?v=" . (@filemtime(__DIR__ . '/../../public/js/code_js_jquerys/admin-videos.js') ?: 1) ?>" defer></script>
    <?php
    echo '</body>';
echo '</html>';
?>
