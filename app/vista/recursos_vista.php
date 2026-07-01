<?php

/* Iniciamos las variables del fichero */
$DESCRIPCION = "forexfalcon.com";
$CLAVES = "formaci&oacute;n, cursos, certificado, 'certificado de transporte', Sevilla, Transporte, Transportista";
$AUTOR = "ForexFalcon";
$TITULO = "Recuros - ForexFalcon";
$ACTIVO = 10;

// Empezamos
echo '<!DOCTYPE html><html lang="es-es" dir="ltr">';
    include_once("recursos/cabecera.php");                                               // <head>...</head>
    echo '<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">';
    echo '<div class="app-wrapper">';
        include_once("recursos/menu.php");                                               // Menú superior fijo
        include_once("recursos/main_recursos.php");                                      // Bloque central
        include_once("recursos/pie.php");                                                // pie de página
    echo '</div>';
    echo '</body>';
    echo '<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>';
    echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"></script>';
echo '</html>';
?>