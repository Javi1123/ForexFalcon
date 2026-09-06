<?php

session_start();

require_once __DIR__ . '/app/core/DB.php';
require_once __DIR__ . '/vendor/autoload.php';

// Carga las claves desde variables de entorno si existen, si no, usa el valor de ejemplo (sustitúyelo por el tuyo).
define('STRIPE_SECRET_KEY', getenv('STRIPE_SECRET_KEY') ?: 'sk_test_51U15FfLpYszdSk8wSfbQDeliZuQW2lzNkpfOJLy9bc3SVJW68VwkkAwDyOd9OMSRCe3RkxtZMTK72oJHWsdg7YMP00PxctUOlj');
define('STRIPE_PUBLISHABLE_KEY', getenv('STRIPE_PUBLISHABLE_KEY') ?: 'pk_test_51U15FfLpYszdSk8w0IJjtFKjrBVEvFCT7XXHrmEud4LGORhVdtJNI7TXny6BSKwBZVGlCdrxDMmM5BdKErcisHyu00DYSzb67L');
define('SITE_URL', 'http://localhost/forexfalcon');   // en local
// define('SITE_URL', 'https://forexfalcon.com');      // en producción

// Moneda del catálogo (código ISO en minúsculas)
define('CURRENCY', 'eur');

define('BASE_PATH', '/forexfalcon');
define('LINKS_PATH', '/forexfalcon/public');
define('VIEWS_BACK_PATH', '/../../app/vista');
// define('VIEWS_PATH', '/app/vista');

// PRIMERO: Obtener la ruta completa de la URL
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); 

// echo "URI: " . $uri . "<br>";

require_once __DIR__ . '/router/config.php';