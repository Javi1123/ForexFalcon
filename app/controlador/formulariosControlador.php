<?php

namespace App\controlador;

use \App\modelo\loginModelo;

class formulariosControlador{
  
  public function acciones() {
    $tipo = $_GET["tipo"];

    require __DIR__ . '/../vista/login_vista.php';
  }

  public function inicio(){
    if(!isset($_GET['tipo'])){
      header("Location: " . BASE_PATH);
      exit();
    }

    $tipo = $_GET["tipo"];

    $usuario = $_REQUEST['usuario'] ?? "";
    $contraseña = $_REQUEST['contraseña'] ?? "";
    $errores = [];

    if($_SERVER['REQUEST_METHOD'] === 'POST'){

      if($usuario == ""){
        $errores['usuario'] = true;
      }

      if($contraseña == ""){
        $errores['contraseña'] = true;
      }

      if(empty($errores)){
        
        $usuarioBase = loginModelo::getUsuario($usuario);
        
        if($usuarioBase && hash('sha256', $contraseña) === $usuarioBase['contrasena']){
          $_SESSION['usuario'] = $usuarioBase['usuario'];
          $_SESSION['nombre'] = $usuarioBase['nombre'];
          $_SESSION['apellido'] = $usuarioBase['apellido'];
<<<<<<< HEAD
          header("Location: " . BASE_PATH . 'recursos');
=======
          header("Location: " . BASE_PATH . '/recursos');
>>>>>>> 6d4c6c7 (Cambios varios y empezado con el inicio de sesion / registro)
          exit();
        }else{
          $errores['login'] = true;
        }
      }
    }

    require __DIR__ . '/../vista/login_vista.php';
  }
    
  public function crear_cuenta(){
    if(!isset($_GET['tipo'])){
      header("Location: " . BASE_PATH);
      exit();
    }

    $usuario = $_REQUEST['usuario'] ?? "";
    $contraseña = $_REQUEST['contraseña'] ?? "";
    $errores = [];

    if($_SERVER['REQUEST_METHOD'] === 'POST'){

      if($usuario == ""){
        $errores['usuario'] = true;
      }

      if($contraseña == ""){
        $errores['contraseña'] = true;
      }

      if(empty($errores)){
        
        $usuarioBase = loginModelo::getUsuario($usuario);
        
        if($usuarioBase && hash('sha256', $contraseña) === $usuarioBase['contrasena']){
          $_SESSION['usuario'] = $usuarioBase['usuario'];
          $_SESSION['nombre_completo'] = $usuarioBase['nombre_completo'];
<<<<<<< HEAD
          header("Location: " . BASE_PATH . 'admin');
=======
          header("Location: " . BASE_PATH . '/admin');
>>>>>>> 6d4c6c7 (Cambios varios y empezado con el inicio de sesion / registro)
          exit();
        }else{
          $errores['login'] = true;
        }
      }
    }

    $tipo = $_GET["tipo"];

    require __DIR__ . '/../vista/login_vista.php';
  }

  public function logout(){
    session_unset();
    session_destroy();
<<<<<<< HEAD
    header("Location: " . BASE_PATH . 'acciones');
=======
    header("Location: " . BASE_PATH . '/acciones');
>>>>>>> 6d4c6c7 (Cambios varios y empezado con el inicio de sesion / registro)
    exit();
  }

}
