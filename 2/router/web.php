<?php

use App\Controllers\IndexController;
use App\Controllers\LoginController;

// Obtener la ruta actual
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Limpiar la ruta - INCLUIR index
$uri = str_replace('/forexfalcon/2', '', $uri);

// Si la URI queda vacía o es solo '/', dejarla como '/'
if ($uri === '' || $uri === '/index') {
  $uri = '/';
}

// Obtener el método HTTP
$method = $_SERVER['REQUEST_METHOD'];

switch ($uri){

  case '/':
    $controller = new IndexController();
    $controller->index();
  break;

  case '/copyTranding':
    require_once "app/views/copyTranding_view.php";
  break;

  default:
    http_response_code(404);
    echo "Page not found";
  break;
}