<?php

session_start();

require_once __DIR__ . '/app/core/DB.php';
require_once __DIR__ . '/app/modelo/funciones.php';
require_once __DIR__ . '/vendor/autoload.php';
// require_once '../config.htaccess';

define('BASE_PATH', './');
define('LINKS_PATH', './public');
define('VIEWS_BACK_PATH', '/../../app/vista');
// define('VIEWS_PATH', '/app/vista');

// 1. PRIMERO: Obtener la ruta completa de la URL
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); 

// echo "URI: " . $uri . "<br>";

require_once __DIR__ . '/router/config.php';