<?php

namespace App\modelo;

use \App\core\DB;

class loginModelo {

  public static function getUsuario($email){

    $pdo = DB::getInstance();
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

}
