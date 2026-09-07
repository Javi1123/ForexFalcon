<?php

use App\controlador\formulariosControlador;
use App\controlador\principalControlador;
use App\controlador\stripeControlador;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = str_replace(BASE_PATH, '', $uri);
$uri = str_replace('', '', $uri);

if ($uri === '' || $uri === '/' || $uri === '/index.php') {
  $uri = '/index';
}

// echo $uri;

switch ($uri){

  // Acciones para la pagina principal
  case '/index':
    $controlador = new principalControlador();
    $controlador-> index();
    break;

  case '/quienes_somos':
    $controlador = new principalControlador();
    $controlador-> quienes_somos();
    break;    

  case '/recursos':
    $controlador = new principalControlador();
    $controlador-> recursos();
    break;

  case '/pago':
    $controlador = new stripeControlador();
    $controlador-> pago();
    break;
    
  case '/guardar':
    $controlador = new stripeControlador();
    $controlador-> guardarDatosPago();
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

