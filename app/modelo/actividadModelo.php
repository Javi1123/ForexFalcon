<?php

namespace App\modelo;

use \App\core\DB;

class actividadModelo {

  public static function insertUltimaVezInicioSesion($email){

    $ip = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    $pdo = DB::getInstance();
    $stmt = $pdo->prepare("INSERT INTO Registro_actividad (Correo, Tipo_evento, Ip_address, User_agent) VALUES (:email, 'login', :ip, :user_agent);");
    $stmt->execute([
      ":email" => $email,
      ":ip" => $ip,
      ":user_agent" => $user_agent,
    ]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  public static function updateUltimaVezInicioSesion($email){
    $pdo = DB::getInstance();
    $stmt = $pdo->prepare("UPDATE Usuarios SET Ultimo_login = NOW(), En_linea = 1 WHERE Correo = :email;");
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  public static function insertUltimaVezCerroSesion($email){

    $ip = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    $pdo = DB::getInstance();
    $stmt = $pdo->prepare("INSERT INTO Registro_actividad (Correo, Tipo_evento, Ip_address, User_agent)VALUES (:email, 'logout', :ip, :user_agent);");
    $stmt->execute([
      ":email" => $email,
      ":ip" => $ip,
      ":user_agent" => $user_agent,
    ]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  public static function updateUltimaVezCerroSesion($email){
    $pdo = DB::getInstance();
    $stmt = $pdo->prepare("UPDATE Usuarios SET Ultimo_logout = NOW(), En_linea = 0 WHERE Correo = :email;");
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }
  
}