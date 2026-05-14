<?php

namespace App\Models;

use App\core\db;

class LoginModel {

  public static function getUser($usuario){
    $pdo = db::getInstance();
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
    $stmt->bindParam(":usuario", $usuario);
    $stmt->execute();
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }
}
