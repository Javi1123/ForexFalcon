<?php

/* Vista de administración (Administración de usuarios): misma estructura que admin_servicios_vista.php */
$DESCRIPCION = "forexfalcon.com";
$CLAVES = "administración";
$AUTOR = "ForexFalcon";
$TITULO = "Administración de usuarios - ForexFalcon";
$ACTIVO = 10;

// Empezamos
echo '<!DOCTYPE html><html lang="es-es" dir="ltr">';
    include_once("recursos/cabecera.php");                                               // <head>...</head>  (carga admin-servicios.css y admin-comun.css porque $vistaAdmin está activa)
    echo '<body class="layout-fixed sidebar-expand-lg bg-body-tertiary p-0">';
    echo '<div class="app-wrapper">';
        include_once("admin/menu_admin.php");                                            // Menú superior fijo + barra lateral
        include_once("admin/main_admin_usuarios.php");                                                    // Bloque central
        include_once("recursos/chat_burbuja.php");                                       // Burbuja de mensajería: el administrador también atiende chats desde aquí
        include_once("recursos/pie.php");                                                // pie de página
    echo '</div>';
    ?>
    <script>
      window.FF_ADMIN = <?= json_encode(['base' => BASE_PATH, 'csrf' => $adminCsrf ?? ''], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    </script>
    <script src="<?= LINKS_PATH . "/js/code_js_jquerys/admin-comun.js?v=" . (@filemtime(__DIR__ . '/../../public/js/code_js_jquerys/admin-comun.js') ?: 1) ?>" defer></script>
    <script src="<?= LINKS_PATH . "/js/code_js_jquerys/admin-usuarios.js?v=" . (@filemtime(__DIR__ . '/../../public/js/code_js_jquerys/admin-usuarios.js') ?: 1) ?>" defer></script>
    <?php
    echo '</body>';
echo '</html>';
?>
