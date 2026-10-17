<?php

namespace App\modelo;

use \App\core\DB;

class actividadModelo {

  public static function insertUltimaVezInicioSesion($email){

    $ip = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    $pdo = DB::getInstance();
    try {
      $stmt = $pdo->prepare("INSERT INTO Registro_actividad (Correo, Tipo_evento, Ip_address, User_agent) VALUES (:email, 'login', :ip, :user_agent);");
      $stmt->execute([
        ":email" => $email,
        ":ip" => $ip,
        ":user_agent" => $user_agent,
      ]);
      
      return $stmt->fetch(\PDO::FETCH_ASSOC);
    
      } catch (\Throwable $th) {
      $_SESSION['toast'] = [
        'tipo'     => 'error',
        'mensaje'  => 'No se ha podido registrar la actividad de entrada, por favor intenta iniciar sesión más tarde.'
      ];

      header("Location: " . BASE_PATH . '/acciones?tipo=inicio');
      exit();
    }
  }

  public static function updateUltimaVezInicioSesion($email){
    $pdo = DB::getInstance();
    
    try {
      $stmt = $pdo->prepare("UPDATE Usuarios SET Ultimo_login = NOW(), En_linea = 1 WHERE Correo = :email;");
      $stmt->bindParam(":email", $email);
      $stmt->execute();

      return $stmt->fetch(\PDO::FETCH_ASSOC);
      
    } catch (\Throwable $th) {
      $_SESSION['toast'] = [
        'tipo'     => 'error',
        'mensaje'  => 'No se ha podido actualizar la actividad de entrada, por favor intenta iniciar sesión más tarde.'
      ];

      header("Location: " . BASE_PATH . '/acciones?tipo=inicio');
      exit();
    }

  }

  public static function insertUltimaVezCerroSesion($email){

    $ip = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    $pdo = DB::getInstance();

    try {
      $stmt = $pdo->prepare("INSERT INTO Registro_actividad (Correo, Tipo_evento, Ip_address, User_agent)VALUES (:email, 'logout', :ip, :user_agent);");
      $stmt->execute([
        ":email" => $email,
        ":ip" => $ip,
        ":user_agent" => $user_agent,
      ]);

      return $stmt->fetch(\PDO::FETCH_ASSOC);

    } catch (\Throwable $th) {
      $_SESSION['toast'] = [
        'tipo'     => 'error',
        'mensaje'  => 'No se ha podido registrar la actividad de salida, por favor intenta iniciar sesión más tarde.'
      ];

      header("Location: " . BASE_PATH . '/recursos');
      exit();
    }
  }

  public static function updateUltimaVezCerroSesion($email){
    $pdo = DB::getInstance();

    try {
      $stmt = $pdo->prepare("UPDATE Usuarios SET Ultimo_logout = NOW(), En_linea = 0 WHERE Correo = :email;");
      $stmt->bindParam(":email", $email);
      $stmt->execute();
      
      return $stmt->fetch(\PDO::FETCH_ASSOC);

    } catch (\Throwable $th) {
      $_SESSION['toast'] = [
        'tipo'     => 'error',
        'mensaje'  => 'No se ha podido actualizar la actividad de entrada, por favor intenta iniciar sesión más tarde.'
      ];

      header("Location: " . BASE_PATH . '/recursos');
      exit();
    }
  }
  
}