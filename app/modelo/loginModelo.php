<?php

namespace App\modelo;

use \App\core\DB;
use \App\modelo\stripeModelo;

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
  // SUSCRIPCIONES
  /////////////////////////////////

  public static function getSuscripcones($email){
    $pdo = DB::getInstance();
    $stmt = $pdo->prepare("SELECT * FROM 'suscripciones' WHERE Correo = ':email'");
    $stmt->execute([
      ":email" => $email
    ]);

    return $stmt;
  }

  public static function setSuscripcones($email,$id_servicio){
    $pdo = DB::getInstance();
    $stmt = $pdo->prepare("INSERT INTO suscripciones (Activado, Fecha_inicio, Fecha_fin, Id_servicio, Correo) VALUES (1, NOW(), NULL, :id_servicio, :email)");
    $stmt->execute([
      ":email" => $email,
      ":id_servicio" => $id_servicio
    ]);

    return $stmt;
  }

  /////////////////////////////////
  // CORREO DESCUENTO
  /////////////////////////////////

  public static function getCorreoDescuento($email){
    $pdo = DB::getInstance();
    $stmt = $pdo->prepare("SELECT * FROM  Correo_descuento WHERE Correo = :email");
    $stmt->execute([
      ":email" => $email
    ]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  public static function setCorreoDescuento($email){
    $codigo = self::generarCupon();

    $stripe = new stripeModelo(STRIPE_SECRET_KEY);

    try{
      $stripe->post('promotion_codes', [
        'promotion[type]'   => 'coupon',
        'promotion[coupon]' => 'descuento_primera_compra',
        'code'   => $codigo,       // el texto que escribirá el cliente
        'max_redemptions' => 1,       // ⚠️ solo se puede usar 1 vez en total
      ]);
    } catch (\Exception $e){
      // Si Stripe rechaza el código (ej: ya existe ese texto),
      // no seguimos guardando nada en tu BD.
      error_log('Error creando promotion code en Stripe: ' . $e->getMessage());
      throw $e;
    }

    $pdo = DB::getInstance();
    $stmt = $pdo->prepare("INSERT INTO Correo_descuento (Correo, Cupon_descuento, Nombre_descuento, Fecha_creacion) VALUES (:email, :codigo, :nombre_descuento, CURDATE())");
    $stmt->execute([
      ":email" => $email,
      ":codigo" => $codigo,
      ":nombre_descuento" => 'descuento_primera_compra'
    ]);

    correoModelo::correo_descuento($email, $codigo);

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
