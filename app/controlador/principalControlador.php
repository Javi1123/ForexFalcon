<?php

namespace App\controlador;

use \App\modelo\loginModelo;

class principalControlador{
  
  public function index() {

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $email = $_POST['email'];
      
      loginModelo::setCorreoDescuento($email);
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

    // Sacamos la inicial
    $inicial = mb_strtoupper(mb_substr($_SESSION['nombre'], 0, 1));

    // Generamos un color "aleatorio" pero consistente en base al nombre completo
    $nombreCompleto = $_SESSION['nombre'] . $_SESSION['apellido'];
    $hash = md5($nombreCompleto);
    $colorAvatar = '#' . substr($hash, 0, 6);

    require_once __DIR__ . '/../../app/vista/recursos_vista.php';
  }

}
