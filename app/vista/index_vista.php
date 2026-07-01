<?php

/* Iniciamos las varialbles del fichero */
$DESCRIPCION = "forexfalcon.com";
$CLAVES = "formaci&oacute;n, cursos, certificado, 'certificado de transporte', Sevilla, Transporte, Transportista";
$AUTOR = "ForexFalcon";
$TITULO = "ForexFalcon";
$ACTIVO = 10;

// Empezamos
echo '<!DOCTYPE html><html lang="es-es" dir="ltr">';
    require_once "main/cabecera.php";                        // <head>...</head>
    echo '<body>';
        include_once("main/menu.php");                       // Menú superior fijo
        include_once("main/video.php");                      // Video Principal
        include_once("main/main_index.php");                 // Bloque central de index
        include_once("main/pie.php");                        // pie de página
    echo '</body>';
echo '</html>';
?>