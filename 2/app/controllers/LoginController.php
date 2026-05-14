<?php

namespace App\Controllers;

class LoginController {

  public function login(){

    $usuario = $_REQUEST['usuario'] ?? "";
    $password = $_REQUEST['password'] ?? "";
    $errores = [];

    if($_SERVER['REQUEST_METHOD'] === 'POST'){

      if($usuario == ""){
        $errores['usuario'] = true;
      }

      if($password == ""){
        $errores['password'] = true;
      }

      if(empty($errores)){

        $usuarioBase = \App\Models\LoginModel::getUser($usuario);

        if($usuarioBase && password_verify($password, $usuarioBase['password'])){
          $_SESSION['id_usuario'] = $usuarioBase['id'];
          $_SESSION['usuario'] = $usuarioBase['usuario'];
          header('Location: ' . BASE_PATH . '/');
          exit();
        } else {
          $errores['login'] = true;
        }
      }
    }

    require VIEWS_PATH . '/index_view.php';
  }

  public function logout(){
    session_unset();
    session_destroy();
    require VIEWS_PATH . '/login_view.php';
  }
}
