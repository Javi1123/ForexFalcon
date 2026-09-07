<?php

namespace App\controlador;

use \App\modelo\loginModelo;
use \App\modelo\stripeModelo;

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

        // PONER FUNCION CORREO Y MANDAR CODIGO (coger codigo del $correo_encontrado)

        header("Location: " . BASE_PATH . "/");
        exit;
      }
    }

    require_once __DIR__ . '/../../app/vista/index_vista.php';
  }

  public function quienes_somos(){
    require_once __DIR__ . '/../../app/vista/quienes_somos_vista.php';
  }
    
  public function recursos(){
    if(!isset($_SESSION['email'])){
      header("Location: " . BASE_PATH . '/acciones?tipo=inicio');
      exit();
    }

    $inicial = mb_strtoupper(mb_substr($_SESSION['nombre'], 0, 1));

    $nombreCompleto = $_SESSION['nombre'] . $_SESSION['apellido'];
    $hash = md5($nombreCompleto);
    $colorAvatar = '#' . substr($hash, 0, 6);

    $stripe = new stripeModelo(STRIPE_SECRET_KEY);
    $servicios = $stripe->getServicios();
    
    $serviciosUsuario = stripeModelo::getServiciosUsuario($_SESSION['email']);

    require_once __DIR__ . '/../../app/vista/recursos_vista.php';
  }

}
