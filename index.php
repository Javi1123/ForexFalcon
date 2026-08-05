<?php

session_start();

require_once __DIR__ . '/app/core/DB.php';
require_once __DIR__ . '/app/modelo/funciones.php';
require_once __DIR__ . '/vendor/autoload.php';
// require_once '../config.htaccess';

// Pasarela del pago utilizar cuando se investigue mas
//\Stripe\Stripe::setApiKey('pk_test_51U15FfLpYszdSk8w0IJjtFKjrBVEvFCT7XXHrmEud4LGORhVdtJNI7TXny6BSKwBZVGlCdrxDMmM5BdKErcisHyu00DYSzb67L');

define('BASE_PATH', '/forexfalcon');
define('LINKS_PATH', '/forexfalcon/public');
define('VIEWS_BACK_PATH', '/../../app/vista');
// define('VIEWS_PATH', '/app/vista');

// PRIMERO: Obtener la ruta completa de la URL
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); 

// echo "URI: " . $uri . "<br>";

require_once __DIR__ . '/router/config.php';