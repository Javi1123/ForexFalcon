<?php

namespace App\modelo;

use \App\core\DB;

class loginModelo {

  public static function getUsuario($usuario){

    $pdo = DB::getInstance();
    $stmt = $pdo->prepare("SELECT * FROM administradores WHERE usuario = :usuario");
    $stmt->bindParam(":usuario", $usuario);
    $stmt->execute();
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

}
