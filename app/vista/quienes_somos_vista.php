<?php

/* Iniciamos las variables del fichero */
$DESCRIPCION = "forexfalcon.com";
$CLAVES = "formaci&oacute;n, cursos, certificado, 'certificado de transporte', Sevilla, Transporte, Transportista";
$AUTOR = "ForexFalcon";
$TITULO = "Quienes somos - ForexFalcon";
$ACTIVO = 10;

// Empezamos
echo '<!DOCTYPE html><html lang="es-es" dir="ltr">';
    include_once("main/cabecera.php");                                               // <head>...</head>
    echo '<body>';
        include_once("main/menu.php");                                               // Menú superior fijo
        include_once("main/main_quienes_somos.php");               // Bloque central de cursos
        include_once("main/pie.php");                                                // pie de página
    echo '</body>';
echo '</html>';
?>