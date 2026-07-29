<?php

namespace App\modelo;

use \App\core\DB;

class loginModelo {

  /////////////////////////////////
  // USUARIO
  /////////////////////////////////

  public static function getUsuario($email){
    $pdo = DB::getInstance();
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE Correo = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }
  
  public static function setUsuario($nombre, $apellido, $email, $telefono, $pais, $fecha_nacimiento, $contraseña){
    $pdo = DB::getInstance();
    
    $stmt = $pdo->prepare("INSERT INTO Usuarios (Correo, Nombre, Apellido, Telefono, Pais, Rol, Fecha_nacimiento, contraseña, Fecha_creacion) VALUES (:email, :nombre, :apellido, :telefono, :pais, 'Usuario', :fecha_nacimiento, SHA2(:contrasena, 256), CURDATE());");
    $stmt->execute([
      ":nombre" => $nombre,
      ":apellido" => $apellido,
      ":email" => $email,
      ":telefono" => $telefono,
      ":pais" => $pais,
      ":fecha_nacimiento" => $fecha_nacimiento,
      ":contrasena" => $contraseña
    ]);
  
    return $stmt;
  }
  
  /////////////////////////////////
  // CORREO DESCUENTO
  /////////////////////////////////

  public static function getCorreoDescuento($email){
    $codigo = self::generarCupon();

    $pdo = DB::getInstance();
    $stmt = $pdo->prepare("SELECT * FROM  Correo_descuento WHERE Correo = :email");
    $stmt->execute([
      ":email" => $email
    ]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  public static function setCorreoDescuento($email){
    $codigo = self::generarCupon();

    $pdo = DB::getInstance();
    $stmt = $pdo->prepare("INSERT INTO Correo_descuento (Correo, Cupon_descuento, Porciento_descuento, Fecha_creacion, Activado, Fecha_activacion) VALUES (:email, :codigo, 10, CURDATE(), false, '')");
    $stmt->execute([
      ":email" => $email,
      ":codigo" => $codigo
    ]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  static function generarCupon($longitud = 8) {
    $caracteres = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
    $max = strlen($caracteres) - 1;
    $codigo = '';

    for ($i = 0; $i < $longitud; $i++) {
      $codigo .= $caracteres[random_int(0, $max)];
    }

    return $codigo;
  }
}
