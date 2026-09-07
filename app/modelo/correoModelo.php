<?php

namespace App\modelo;

class correoModelo {
  /////////////////////////////////
  // CORREO MANDAR DE RESGISTRO
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

  //////////////////////////////////////////////////////
  // CORREO MANDAR PARA NOSOTROS DEL REGISTRO
  //////////////////////////////////////////////////////

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


  //////////////////////////////////////////////////////
  // CORREO MANDAR DE FORMULARIO DESCUENTO
  //////////////////////////////////////////////////////

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