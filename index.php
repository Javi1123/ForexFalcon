<?php

session_start();

require_once __DIR__ . '/app/core/DB.php';
require_once __DIR__ . '/vendor/autoload.php';

// Carga las claves desde variables de entorno si existen, si no, usa el valor de ejemplo (sustitúyelo por el tuyo).
define('STRIPE_SECRET_KEY', getenv('STRIPE_SECRET_KEY') ?: 'sk_test_51U15FfLpYszdSk8wSfbQDeliZuQW2lzNkpfOJLy9bc3SVJW68VwkkAwDyOd9OMSRCe3RkxtZMTK72oJHWsdg7YMP00PxctUOlj');
define('STRIPE_PUBLISHABLE_KEY', getenv('STRIPE_PUBLISHABLE_KEY') ?: 'pk_test_51U15FfLpYszdSk8w0IJjtFKjrBVEvFCT7XXHrmEud4LGORhVdtJNI7TXny6BSKwBZVGlCdrxDMmM5BdKErcisHyu00DYSzb67L');
define('SITE_URL', 'http://localhost/forexfalcon');   // en local
// define('SITE_URL', 'https://forexfalcon.com');      // en producción

// Chat de mensajería (Pusher Channels). Claves en https://dashboard.pusher.com → tu app → "App Keys".
// Igual que Stripe: se leen de variables de entorno si existen; si no, sustituye los TU_... por los tuyos.
// Mientras sigan los TU_..., la burbuja del chat se muestra pero avisa de que falta configurarlo.
define('PUSHER_APP_ID',  getenv('PUSHER_APP_ID')  ?: '2194277');
define('PUSHER_KEY',     getenv('PUSHER_KEY')     ?: '79b2bbaff53a39bf6b71');
define('PUSHER_SECRET',  getenv('PUSHER_SECRET')  ?: 'a526b64f28603cedce06');
define('PUSHER_CLUSTER', getenv('PUSHER_CLUSTER') ?: 'eu');   // ej: 'eu', 'mt1', 'us2'...

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