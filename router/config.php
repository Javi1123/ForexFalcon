<?php

use App\controlador\adminControlador;
use App\controlador\tablasControlador;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = str_replace(BASE_PATH, '', $uri);
$uri = str_replace('', '', $uri);

if ($uri === '' || $uri === '/' || $uri === '/index.php') {
  $uri = '/index';
}

switch ($uri){

  case '/index':
    require_once __DIR__ . '/../app/vista/index_vista.php';
    break;
      

    
  // Vistas de la pagina admin
  case '/login':
    $controlador = new adminControlador();
    $controlador-> login();
    break;
          
  case '/logout':
    $controlador = new adminControlador();
    $controlador-> logout();
    break;

  case '/admin':
    $controlador = new adminControlador();
    $controlador-> adminIndex();
    break;
  
  case '/sugerenciaTabla':
    $controladorTablas = new tablasControlador();
    $controladorTablas-> indexSugerencias();
    break;

  case '/alumnosTabla':
    $controladorTablas = new tablasControlador();
    $controladorTablas-> indexAlumnos();
    break;

  case '/cursosTabla':
    $controladorTablas = new tablasControlador();
    $controladorTablas-> indexCursos();
    break;

  case '/matriculadosTabla':
    $controladorTablas = new tablasControlador();
    $controladorTablas-> indexMatriculados();
    break;

  case '/recibosTabla':
    $controladorTablas = new tablasControlador();
    $controladorTablas-> indexRecibos();
    break;



  // Vistas de la pagina principal
  case '/cursos':
    require_once __DIR__ . '/../app/vista/cursos.php';
    break;

  case '/sugerencias':
    require_once __DIR__ . '/../app/vista/sugerencias.php';
    break;

  case '/quienes_somos':
    require_once __DIR__ . '/../app/vista/quienes_somos.php';
    break;

  case '/donde_estamos':
    require_once __DIR__ . '/../app/vista/donde_estamos.php';
    break;

  case '/privacidad':
    require_once __DIR__ . '/../app/vista/privacidad.php';
    break;
  
  case '/aviso_legal':
    require_once __DIR__ . '/../app/vista/aviso_legal.php';
    break;

  case '/mision_visionyvalores':
    require_once __DIR__ . '/../app/vista/mision_visionyvalores.php';
    break;

  case '/registrarse':
    require_once __DIR__ . '/../app/vista/registrarse.php';
    break;

  default:
    http_response_code(404);
    echo "Page not found";
    break;
}

