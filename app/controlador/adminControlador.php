<?php

namespace App\controlador;

use \App\modelo\loginModelo;
use \App\modelo\tablasModelo;

class adminControlador{
  
  public function login(){
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
          header("Location: " . BASE_PATH . '/admin');
          exit();
        }else{
          $errores['login'] = true;
        }
      }
    }
    
    require __DIR__ . '/../vista/login_vista.php';
  }
    
  public function logout(){
    session_unset();
    session_destroy();
    header("Location: " . BASE_PATH . '/login');
    exit();
  }
  
  public function adminIndex(){
    if(!isset($_SESSION['usuario'])){
      header("Location: " . BASE_PATH . '/login');
      exit();
    }
    $usuario = $_SESSION['usuario'];
    $nombre_completo = $_SESSION['nombre_completo'];

    $sugerecias = tablasModelo::getAllSugerecias();
    $alumnos = tablasModelo::getAllAlumnos();
    $cursos = tablasModelo::getAllCursos();
    $recibos = tablasModelo::getAllRecibos();
    $matriculados = tablasModelo::getAllMatriculados();
    
    $allSugerencias = count($sugerecias);
    $allAlumnos = count($alumnos);
    $allCursos = count($cursos);
    $allRecibos = count($recibos);
    $allMatriculados = count($matriculados);

    require_once __DIR__ . '/../vista/admin_vista.php';
  }

}
