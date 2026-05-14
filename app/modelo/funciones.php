<?php

/*************************************************************************************
Funcion de poner un int a string
*************************************************************************************/
function numero_a_texto($nNumero){
	$numeros[] = "cero";
	$numeros[] = "uno";
	$numeros[] = "dos";
	$numeros[] = "tres";
	$numeros[] = "cuatro";
	$numeros[] = "cinco";
	$numeros[] = "seis";
	$numeros[] = "siete";
	$numeros[] = "ocho";
	$numeros[] = "nueve";
	$numeros[] = "diez";
	return $numeros[$nNumero];
};

/*************************************************************************************
Validacion de DNI
*************************************************************************************/
function validarDocumento($documento){
  if($documento == ""){
    $_SESSION["popErrores"] = [
      "icon" => "error",
      "titulo" => "Tipo de documentación no seleccionada:",
      "texto" => "Por favor, seleccione un tipo de documentación"
    ];
    return false;
  }
  return true;
}

/*************************************************************************************
Validacion de DNI
*************************************************************************************/
function validarDNI($documento,$DNI){
  if($documento == "NIF"){
    if(preg_match('/^[0-9]{8}[A-Z]+$/', $DNI) == 0){
      $_SESSION["popErrores"] = [
        "icon" => "error",
        "titulo" => "NIF no valido:",
        "texto" => "Por favor, revise si su NIF tiene los caracteres basicos"
      ];
      return false;
    }
    return true;
  } else if($documento == "NIE"){
    if(preg_match('/^[XYZxyz][0-9]{7,8}[A-Za-z]$/', $DNI) == 0){
      $_SESSION["popErrores"] = [
        "icon" => "error",
        "titulo" => "NIE no valido:",
        "texto" => "Por favor, revise si su NIE tiene los caracteres basicos"
      ];
      return false;
    }
    return true;
  } else if($documento == "pasaporte"){
    if(preg_match('/^[A-Za-z]{1,2}[0-9]{6,9}$/', $DNI) == 0){
      $_SESSION["popErrores"] = [
        "icon" => "error",
        "titulo" => "Pasaporte no valido:",
        "texto" => "Por favor, revise si su pasaporte tiene los caracteres basicos"
      ];
      return false;
    }
    return true;
  } else if($documento == "") {
    return true;
  }

}

/*************************************************************************************
Validacion de nombre
*************************************************************************************/
function validarNombre($nombre){
  if(strlen($nombre) > 15 || preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $nombre) == 0){
    $_SESSION["popErrores"] = [
      "icon" => "error",
      "titulo" => "Nombre no valido:",
      "texto" => "Por favor, revise si su nombre contiene números o esta vacio"
    ];
    return false;
  }
  return true;
}

/*************************************************************************************
Validacion de email
*************************************************************************************/
function validarEmail($email){
  if(preg_match('/^([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})?$/', $email) == 0){
    $_SESSION["popErrores"] = [
      "icon" => "error",
      "titulo" => "Email no valido:",
      "texto" => "Por favor, revise si su email esta mal escrito"
    ];
    return false;
  }
  return true;
}

/*************************************************************************************
Validacion de apellido
*************************************************************************************/
function validarApellido($apellido){
  if(preg_match('/^(([a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+){3,})?$/', $apellido) == 0){
    $_SESSION["popErrores"] = [
      "icon" => "error",
      "titulo" => "Apellido no valido:",
      "texto" => "Por favor, revise si su apellido contiene números o esta vacio"
    ];
    return false;
  }
  return true;
}

/*************************************************************************************
Validacion de telefono
*************************************************************************************/
function validarTelefono($telefono){
  if(preg_match('/^([0-9]{9})?$/', $telefono) == 0){
    $_SESSION["popErrores"] = [
      "icon" => "error",
      "titulo" => "Telefono no valido:",
      "texto" => "Por favor, revise si su numero de telefono es valido"
    ];
    return false;
  }
  return true;
}

/*************************************************************************************
Validacion de direccion
*************************************************************************************/
function validarDireccion($direccion){
  if(preg_match('/^C:\s*[^,]+(?:,\s*Nº\s*[^,]+)?(?:,\s*Piso\s*[^,]+)?(?:,\s*Puerta\s*[^,]+)?$/i', $direccion) == 0){
    $_SESSION["popErrores"] = [
      "icon" => "error",
      "titulo" => "La direccion no esta bien escrita:",
      "texto" => "Por favor, mire el siguinte ejemplo C: Valle de Lora, Nº 1"
    ];
    return false;
  }
  return true;
}

/*************************************************************************************
Validacion de si hay mas de 1 error en el formulario
*************************************************************************************/
function validarSiHayMasErrores($errores){
  if(count($errores) > 1){
    $_SESSION["popErrores"] = [
      "icon" => "error",
      "titulo" => "Datos de Entrada Erroneos:",
      "texto" => "Por favor, revise los datos e introduzcalos correctamente"
    ];
  }
}

/*************************************************************************************
Validacion de si hay mas de 10 caracteres en campo de sugerencia
*************************************************************************************/
function validarSugerencia($sugerencia){
  if(strlen($sugerencia) < 10){
    $_SESSION["popErrores"] = [
      "icon" => "error",
      "titulo" => "Sus sugerencias son poco extensas:",
      "texto" => "Por favor, revise que la sugerencia y/o reclamación que nos propone contiene más de 10 caracteres"
    ];
    return false;
  }
  return true;
}

/*************************************************************************************
Validacion de si hay mas de 10 caracteres en campo de interes
*************************************************************************************/
function validarInteres($interes){
  if(strlen($interes) < 10){
    $_SESSION["popErrores"] = [
      "icon" => "error",
      "titulo" => "Sus intereses son poco extensos:",
      "texto" => "Por favor, revise que su intereses que nos propone contiene más de 10 caracteres"
    ];
    return false;
  }
  return true;
}

// Por ejemplo $ip = '128.192.0.1';
function es_ip($ip){
    $IP = false;
    if (filter_var($ip, FILTER_VALIDATE_IP)) {
		$lIP = true; // Es una IP válida.
    }
    return $lIP;
}

/*************************************************************************************
Envío de correo para nosotros
*************************************************************************************/
function correos_nosotros($c_Asunto, $c_DatosAlumno, $t_form){
// Varios destinatarios
// $para  = 'info@trainingtic.com' . ', '; 
$cPara  = 'info@certitransporte.com';


// $mensaje = wordwrap($mensaje, 70, "\r\n");

// Para enviar un correo HTML, debe establecerse la cabecera Content-type
$cCabeceras  = 'MIME-Version: 1.0' . "\r\n";
$cCabeceras .= "Content-type: text/html; charset=UTF-8" . "\r\n"; 
/**/
// Cabeceras adicionales
$cCabeceras .= 'To: Certitransporte <info@certitransporte.com>' . "\r\n";
$cCabeceras .= 'From: ' .$t_form. ' <info@certitransporte.com>' . "\r\n";
//$cCabeceras .= 'From: Alumno US '.$cGrupo.' <oposiciones@trainingtic.com>';
/*
$cCabeceras .= 'Cc: redes@trainingtic.com' . "\r\n";
$cCabeceras .= 'Bcc: ingles@trainingtic.com' . "\r\n";
*/

// Enviarlo
return mail($cPara, $c_Asunto, $c_DatosAlumno, $cCabeceras);
}

/*************************************************************************************
Envío de correo al alumno formación
*************************************************************************************/
function correos_alumno_formación($cNombre, $cCorreo){
// Uno o Varios destinatarios
$para  = $cCorreo;

// título
$título = 'Gracias por registrarse, Certitransporte';

// mensaje
$mensaje = 'Hola '.$cNombre.', le enviamos este correo de confirmación para que sepa que nos ha llegado la solicitud';

$mensaje .= '

Gracias, por darnos los requisitos para formarte.

Atentamente,
El equipo de Certitransporte

--
TrainingTIC
www.certitransporte.com
Avda. San Francisco Javier, nº 22
Edificio HERMES, 1ª Planta, Nº 14
41018 - Sevilla
Teléfono: 671 355 000
Horario: de 9:00 a 20:00 h. días laborales de lunes a viernes y sábados de 10:00 a 14:00 h.
';
//$mensaje = wordwrap($mensaje, 70, "\r\n");

$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
$cabeceras .= 'Content-type: text/plain; charset=UTF-8' . "\r\n";
//$cabeceras .= 'To: ' . $cCorreo . "\r\n";
$cabeceras .= 'From: Certitransporte <noreply@tecnologiayformacion.com>' . "\r\n";

// Enviarlo
return mail($para, $título, $mensaje, $cabeceras);
}
?>