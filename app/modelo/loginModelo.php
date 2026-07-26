<?php

namespace App\modelo;

use \App\core\DB;

class loginModelo {

  public static function getUsuario($email){
    $pdo = DB::getInstance();
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE Correo = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  public static function setUsuario($nombre, $apellido, $email, $telefono, $pais, $fecha_nacimiento, $contraseña){
    $pdo = DB::getInstance();

    $stmt = $pdo->prepare("INSERT INTO Usuarios (Correo, Telefono, Pais, Fecha_nacimiento, contraseña, Fecha_creacion, Nombre, Apellido) VALUES (:email, :telefono, :pais, :fecha_nacimiento, SHA2(:contrasena, 256), CURDATE(), :nombre, :apellido);");
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

}
