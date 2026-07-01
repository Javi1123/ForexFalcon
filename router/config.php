<?php

use App\controlador\formulariosControlador;
use App\controlador\tablasControlador;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = str_replace(BASE_PATH, '', $uri);
$uri = str_replace('', '', $uri);

if ($uri === '' || $uri === '/' || $uri === '/index.php') {
  $uri = '/index';
}

// $_SESSION['usuario'] = "asd";
// echo $uri;

switch ($uri){

  // Acciones para la pagina principal
  case '/index':
    require_once __DIR__ . '/../app/vista/index_vista.php';
    break;

  case '/quienes_somos':
    require_once __DIR__ . '/../app/vista/quienes_somos_vista.php';
    break;    

  // Acciones para formulario de inicio_de_sesion/registrarse
  case '/acciones':
    $controlador = new formulariosControlador();
    $controlador-> acciones();
    break;

  case '/inicio_sesion':
    $controlador = new formulariosControlador();
    $controlador-> inicio();
    break;

  case '/crear_cuenta':
    $controlador = new formulariosControlador();
    $controlador-> crear_cuenta();
    break;
          
  case '/logout':
    $controlador = new formulariosControlador();
    $controlador-> logout();
    break;

  default:
    http_response_code(404);
    echo "Page not found";
    break;
}

