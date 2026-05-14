<?php

/* Iniciamos las varialbles del fichero */
$DESCRIPCION = "forexfalcon.com";
$CLAVES = "formaci&oacute;n, cursos, certificado, 'certificado de transporte', Sevilla, Transporte, Transportista";
$AUTOR = "TrainingTIC";
$TITULO = "Privacidad - Certitransporte";
$ACTIVO = 10;

// Empezamos
echo '<!DOCTYPE html><html lang="es-es" dir="ltr">';
    include_once("min/cabecera.php");                                                     // <head>...</head>
    echo '<body>';
        include_once("min/menu.php");                                                     // Menú superior fijo
        include_once("min/main_privacidad/main_privacidad.php");                          // Bloque central de cursos
        include_once("min/pie.php");                                                      // pie de página
    echo '</body>';
echo '</html>';
?>