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

    self::correo_descuento($email, $codigo);

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

  /////////////////////////////////
  // CORREO MANDAR
  /////////////////////////////////

  public static function correo_registro($cNombre, $cCorreo){
    // Uno o Varios destinatarios
    $para  = $cCorreo;

    // título
    $título = 'Gracias por registrarse, atentamente Forexfalcon.';

    // mensaje
    $mensaje = 'Hola '.$cNombre.', le enviamos este correo ...';

    $mensaje .= '

    Gracias, ...

    Atentamente,
    El equipo de Forexfalcon

    ------------------------
    Forexfalcon
    www.forexfalcon.com
    forexfalcon@gmail.com
    ';
    //$mensaje = wordwrap($mensaje, 70, "\r\n");

    $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
    $cabeceras .= 'Content-type: text/plain; charset=UTF-8' . "\r\n";
    //$cabeceras .= 'To: ' . $cCorreo . "\r\n";
    $cabeceras .= 'From: Forexfalcon <noreply@tecnologiayformacion.com>' . "\r\n";

    // Enviarlo
    return mail($para, $título, $mensaje, $cabeceras);
  }

  public static function correo_nosotros($c_Asunto, $c_DatosAlumno){
    // Varios destinatarios
    // $para  = '---@---.com' . ', '; 
    $cPara  = 'javierdiazsoriano1@gmail.com';

    // $mensaje = wordwrap($mensaje, 70, "\r\n");

    // Para enviar un correo HTML, debe establecerse la cabecera Content-type
    $cCabeceras  = 'MIME-Version: 1.0' . "\r\n";
    $cCabeceras .= "Content-type: text/html; charset=UTF-8" . "\r\n"; 
    /**/
    // Cabeceras adicionales
    $cCabeceras .= 'To: Forexfalcon <javierdiazsoriano1@gmail.com>' . "\r\n";
    $cCabeceras .= 'From: Forexfalcon <javierdiazsoriano1@gmail.com>' . "\r\n";
    /*
    $cCabeceras .= 'Cc: ---@---.com' . "\r\n";
    $cCabeceras .= 'Bcc: ---@---.com' . "\r\n";
    */

    // Enviarlo
    return mail($cPara, $c_Asunto, $c_DatosAlumno, $cCabeceras);
  }

  public static function correo_descuento($cCorreo, $codigo){
    // Uno o Varios destinatarios
    $para  = $cCorreo;

    // título
    $título = '¡Tu código de descuento exclusivo en ForexFalcon te espera!';

    // mensaje
    $mensaje = <<<EOT
Hola,

En ForexFalcon sabemos que dar el primer paso hacia la libertad financiera es una gran decisión. Por eso, queremos acompañarte con un beneficio especial.

✅ CÓDIGO DE DESCUENTO: {$codigo}
✅ DESCUENTO APLICADO: 10% en tu primera suscripción.

¿Cómo canjearlo?
1. Accede a nuestra plataforma o sección de planes.
2. Elige el servicio que mejor se adapte a tu objetivo.
3. Introduce el código al momento del pago y disfruta de tu descuento.

Recuerda que en ForexFalcon no solo obtienes herramientas tecnológicas (bots, copy trading y análisis semanal), sino el respaldo humano de un mentor 1:1 y una comunidad colaborativa que te guiará paso a paso.

No dejes pasar esta oportunidad. Tu constancia y nuestro acompañamiento son la fórmula para resultados sostenibles.

¡Nos vemos dentro!

Gracias,
El equipo de ForexFalcon

--------------------
ForexFalcon
www.forexfalcon.com
forexfalcon@gmail.com
EOT;
    //$mensaje = wordwrap($mensaje, 70, "\r\n");

    $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
    $cabeceras .= 'Content-type: text/plain; charset=UTF-8' . "\r\n";
    //$cabeceras .= 'To: ' . $cCorreo . "\r\n";
    $cabeceras .= 'From: ForexFalcon <noreply@tecnologiayformacion.com>' . "\r\n";

    // Enviarlo
    return mail($para, $título, $mensaje, $cabeceras);
}

}
