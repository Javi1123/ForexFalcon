<?php

namespace App\controlador;

use \App\modelo\loginModelo;
use \App\modelo\stripeModelo;
use \App\controlador\chatControlador;

class principalControlador{
  
  public function index() {

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $email = $_POST['email'];

      $correo_encontrado = loginModelo::getCorreoDescuento($email);

      if ($correo_encontrado) {
        // Ya existe -> no insertamos, solo notificamos
        $_SESSION['toast'] = [
          'tipo'     => 'error',
          'mensaje'  => 'Ya existe este correo electrónico con un descuento, no lo puedes volver a canjear.'
        ];

        header("Location: " . BASE_PATH . "/");
        exit;

      } else {
        // No existe -> creamos la cuenta
        loginModelo::setCorreoDescuento($email);
        
        $_SESSION['toast'] = [
          'tipo'    => 'success',
          'mensaje' => 'Codigo obtenido correctamente, mira tu correo. ¡Ya puedes usarlo!'
        ];

        header("Location: " . BASE_PATH . "/");
        exit;
      }
    }

    require_once __DIR__ . '/../../app/vista/index_vista.php';
  }

  public function quienes_somos(){
    require_once __DIR__ . '/../../app/vista/quienes_somos_vista.php';
  }
    
  // /recursos: página de bienvenida de la zona privada
  public function recursos(){
    $this->comprobarAcceso();

    $vistaBienvenida = true;

    $inicial = mb_strtoupper(mb_substr($_SESSION['nombre'], 0, 1));

    $nombreCompleto = $_SESSION['nombre'] . $_SESSION['apellido'];
    $hash = md5($nombreCompleto);
    $colorAvatar = '#' . substr($hash, 0, 6);

    require_once __DIR__ . '/../../app/vista/recursos_vista.php';
  }

  // /tienda: la tienda de servicios (antes estaba en /recursos)
  public function tienda(){
    $this->comprobarAcceso();

    $vistaTienda = true;

    $inicial = mb_strtoupper(mb_substr($_SESSION['nombre'], 0, 1));

    $nombreCompleto = $_SESSION['nombre'] . $_SESSION['apellido'];
    $hash = md5($nombreCompleto);
    $colorAvatar = '#' . substr($hash, 0, 6);

    $stripe = new stripeModelo(STRIPE_SECRET_KEY);
    $servicios = $stripe->getServicios();
    
    $serviciosUsuario = stripeModelo::getServiciosUsuario($_SESSION['email']);

    // Datos extra para enriquecer la tienda. Si algo falla, la tienda se muestra igual (solo sin estos detalles).
    $tiposServicio = [];      // CÓDIGO => 'video' | 'imagenes' | 'chat'
    $contratados   = [];      // CÓDIGO => true (suscripciones vigentes del usuario)
    try {
      $tiposServicio = \App\modelo\adminServiciosModelo::tipos();
      foreach (\App\modelo\suscripcionModelo::activasDeUsuario($_SESSION['email']) as $c) {
        $contratados[strtoupper($c['Id_servicio'])] = true;
      }
    } catch (\Throwable $e) {
      error_log('Tienda: no se pudieron leer tipos/suscripciones: ' . $e->getMessage());
    }

    require_once __DIR__ . '/../../app/vista/recursos_vista.php';
  }

  // Sin sesión -> login; administradores -> su panel
  private function comprobarAcceso(){
    if(!isset($_SESSION['email'])){
      $_SESSION['toast'] = [
        'tipo'    => 'error',
        'icono'   => 'fa-user-lock',
        'titulo'  => 'Sin sesión',
        'mensaje' => 'No has iniciado sesión, así que no puedes entrar en tu zona privada. Inicia sesión para continuar.'
      ];
      header("Location: " . BASE_PATH . '/acciones?tipo=inicio');
      exit();
    } else if(chatControlador::esAdmin()){
      header("Location: " . BASE_PATH . '/admin');
      exit();
    }
  }

}
