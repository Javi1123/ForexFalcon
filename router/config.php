<?php

use App\controlador\formulariosControlador;
use App\controlador\principalControlador;
use App\controlador\stripeControlador;
use App\controlador\chatControlador;
use App\controlador\adminControlador;
use App\controlador\adminVideosControlador;
use App\controlador\adminUsuariosControlador;
use App\controlador\servicioControlador;
use App\controlador\videoControlador;

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

  case '/tienda':
    $controlador = new principalControlador();
    $controlador-> tienda();
    break;

  case '/pago':
    $controlador = new stripeControlador();
    $controlador-> pago();
    break;
    
  case '/guardar':
    $controlador = new stripeControlador();
    $controlador-> guardarDatosPago();
    break;

  // Chat de mensajería rápida (burbuja de /recursos)
  case '/chat/estado':
    $controlador = new chatControlador();
    $controlador-> estado();
    break;

  case '/chat/crear':
    $controlador = new chatControlador();
    $controlador-> crear();
    break;

  case '/chat/pendientes':
    $controlador = new chatControlador();
    $controlador-> pendientes();
    break;

  case '/chat/asignar':
    $controlador = new chatControlador();
    $controlador-> asignar();
    break;

  case '/chat/mensaje':
    $controlador = new chatControlador();
    $controlador-> mensaje();
    break;

  case '/chat/historial':
    $controlador = new chatControlador();
    $controlador-> historial();
    break;

  case '/chat/cerrar':
    $controlador = new chatControlador();
    $controlador-> cerrar();
    break;

  case '/chat/pusher_auth':
    $controlador = new chatControlador();
    $controlador-> pusher_auth();
    break;

  // Panel de administración, usuarios y actividad (solo administradores)
  case '/admin':
    $controlador = new adminUsuariosControlador();
    $controlador-> panel();
    break;

  case '/admin/usuarios':
    $controlador = new adminUsuariosControlador();
    $controlador-> usuarios();
    break;

  case '/admin/usuarios/ficha':
    $controlador = new adminUsuariosControlador();
    $controlador-> ficha();
    break;

  case '/admin/usuarios/editar':
    $controlador = new adminUsuariosControlador();
    $controlador-> editar();
    break;

  case '/admin/usuarios/rol':
    $controlador = new adminUsuariosControlador();
    $controlador-> rol();
    break;

  case '/admin/usuarios/eliminar':
    $controlador = new adminUsuariosControlador();
    $controlador-> eliminar();
    break;

  case '/admin/actividad':
    $controlador = new adminUsuariosControlador();
    $controlador-> actividad();
    break;

  case '/admin/actividad/exportar':
    $controlador = new adminUsuariosControlador();
    $controlador-> exportar();
    break;

  // Administración de servicios (solo administradores): vista + acciones JSON
  case '/admin/servicios':
    $controlador = new adminControlador();
    $controlador-> servicios();
    break;

  case '/admin/servicios/crear':
    $controlador = new adminControlador();
    $controlador-> crear();
    break;

  case '/admin/servicios/editar':
    $controlador = new adminControlador();
    $controlador-> editar();
    break;

  case '/admin/servicios/precio':
    $controlador = new adminControlador();
    $controlador-> precio();
    break;

  case '/admin/servicios/estado':
    $controlador = new adminControlador();
    $controlador-> estado();
    break;

  case '/admin/servicios/sincronizar':
    $controlador = new adminControlador();
    $controlador-> sincronizar();
    break;

  // Página de un servicio contratado (la barra lateral enlaza aquí) y entrega protegida de vídeos
  case '/servicio':
    $controlador = new servicioControlador();
    $controlador-> ver();
    break;

  case '/video/ver':
    $controlador = new videoControlador();
    $controlador-> ver();
    break;

  // Vídeos de un servicio (solo administradores): vista + acciones JSON
  case '/admin/videos':
    $controlador = new adminVideosControlador();
    $controlador-> pagina();
    break;

  case '/admin/videos/subir':
    $controlador = new adminVideosControlador();
    $controlador-> subir();
    break;

  case '/admin/videos/cancelar':
    $controlador = new adminVideosControlador();
    $controlador-> cancelar();
    break;

  case '/admin/videos/renombrar':
    $controlador = new adminVideosControlador();
    $controlador-> renombrar();
    break;

  case '/admin/videos/eliminar':
    $controlador = new adminVideosControlador();
    $controlador-> eliminar();
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

