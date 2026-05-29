<?php

  use \App\modelo\tablasModelo;

  $errores = [];
  $errorCaptcha = false;

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $documento = isset($_POST["documento"]) ? $_POST["documento"] : "";
    $DNI = trim($_POST["DNI"]) ?? "";
    $nombre = trim($_POST["nombre"]) ?? "";
    $email = trim($_POST["email"]) ?? "";
    $apellido = trim($_POST["apellido"]) ?? "";
    $telefono = trim($_POST["telefono"]) ?? "";
    $nacimiento = $_POST["nacimiento"] ?? "";
    $direccion = $_POST["direccion"] ?? "";

    $interes = $_POST['interes'] ?? '';
    $captcha = trim($_POST["captcha"]) ?? "";
    $t_form = "Registro Certitransporte";

    if(is_numeric($captcha)){
      $num1 = $_SESSION["numeros"][0];
      $num2 = $_SESSION["numeros"][1];
      $num3 = $_SESSION["numeros"][2];
    
      $_SESSION["suma"] = $num1 + $num2 + $num3;

      if ($captcha != $_SESSION["suma"]) {
        array_push($errores, "captcha");
      }
    }

    if (!validarDocumento($documento))                    array_push($errores, "documento");
    if (!validarDNI($documento,$DNI))                     array_push($errores, "DNI");
    if (!validarNombre($nombre))                          array_push($errores, "nombre");
    if (!validarEmail($email))                            array_push($errores, "email");
    if (!validarApellido($apellido))                      array_push($errores, "apellido");
    if (!validarTelefono($telefono))                      array_push($errores, "telefono");
    // if (!validarnNacimiento($nacimiento))                    array_push($errores, "nacimiento");
    if (!validarDireccion($direccion))                  array_push($errores, "direccion");
    if (!validarInteres(strip_tags($interes)))            array_push($errores, "interes");

    validarSiHayMasErrores($errores);

    if(!$errores && correos_alumno_formación($nombre,$email)){
      $_SESSION["popErrores"] = [
        "icon" => "success",
        "titulo" => "Se ha registrado:",
        "texto" => "Gracias, por registrarte pronto nos pondremos en contacto contigo para que puedamos saber el curso que quieres realizar con nosotros.",
      ];
      
      tablasModelo::insertAlumnos($DNI, $nombre, $apellido, $email, $telefono, $direccion, $nacimiento);

      $c_Asunto = $nombre." se a registrado con nosotros para poder inscribirse.";
      $c_DatosAlumno = "El usuario se a registrado desde forexfalcon en el formulario de registro para poder tener clases de formación de " .$t_form. "<br><br>";
      $c_DatosAlumno .= "<b>".$documento.":</b> ".$DNI. "<br>";
      $c_DatosAlumno .= "<b>Nombre:</b> ".$nombre. "<br>";
      $c_DatosAlumno .= "<b>Apellido:</b> " .$apellido. "<br>";
      $c_DatosAlumno .= "<b>Correo:</b> " .$email. "<br>";
      $c_DatosAlumno .= "<b>Telefono:</b> " .$telefono. "<br>";
      $c_DatosAlumno .= "<b>Nacimiento:</b> " .$nacimiento. "<br>";
      $c_DatosAlumno .= "<b>Direccion:</b> " .$direccion. "<br>";
      $c_DatosAlumno .= "<b>Fecha:</b> " .date("F j, Y, g:i a"). "<br>";
      $c_DatosAlumno .= "<b>Interes:</b> " .$interes;

      correos_nosotros($c_Asunto, $c_DatosAlumno, $t_form);

      $DNI = "";
      $nombre = "";
      $email = "";
      $apellido = "";
      $telefono = "";
      $direccion = "";
      $interes = "";
    }
    
    if(in_array("captcha",$errores)){
      $errorCaptcha = true;
      $errores = [];
      $_SESSION["popErrores"] = [
        "icon" => "error",
        "titulo" => "Suma de Control Erronea:",
        "texto" => "Por favor, revise los datos e introduzcalos correctamente"
      ];
    }

  }

  $num1 = rand(1, 10);
  $num2 = rand(1, 10);
  $num3 = rand(1, 10);

  $_SESSION["numeros"] = [$num1,$num2,$num3];
?>

<!-- Zona Poppopers -->
<?php if(isset($_SESSION["popErrores"])) : ?>
  <script>
    const popErrores = () =>{
      Swal.fire({
        icon: "<?= $_SESSION["popErrores"]["icon"] ?>",
        title: "<?= $_SESSION["popErrores"]["titulo"] ?>",
        text: "<?= $_SESSION["popErrores"]["texto"] ?>",
      });
    }

    popErrores();
  </script>
<?php 
  unset($_SESSION["popErrores"]);
  endif;
?>

<!-- Zona formulario -->
<main>
  <div class="container mb-3">
    <h2 class="mt-4 mb-3 text-center negrita"><i class="fas fa-address-card red"></i> Formulario de formación</h2>

    <div class="container">
      <p class="parrafosDescriptivos negrita orange">Cuentanos en que te quieres formar:</p>
      <p class="parrafosDescriptivos">El siguiente formulario tiene como objetivo conocer tus intereses formativos, para así poder ofrecerte información personalizada, recursos relevantes y convocatorias adaptadas a tu perfil. Tus respuestas nos ayudarán a mejorar nuestros programas y a mantener una comunicación más cercana y efectiva contigo.</p>
      <p class="parrafosDescriptivos">Queremos acompañarte en tu desarrollo profesional y contribuir juntos a entornos más seguros. Agradecemos de antemano tu participación y el tiempo dedicado a este formulario.</p>
    </div>

    <div class="container mt-5">
      <div class="row justify-content-center">
        <div class="card col-11 col-sm-9 col-md-10 col-xl-10 p-3">
          <form id="formulario" action="<?= BASE_PATH . "acciones" ?> " method="post">

            <div class="row mb-3">
              <div class="col-sm-12 col-md-6 col-xl-6 mb-2">
                <div class="form-group">
                  <label for="documento" class="form-label <?= (in_array("documento",$errores)) ? "red" : ""?>"><i class="fa fa-book <?= (in_array("documento",$errores)) ? "red" : ""?>"></i> Documentación:</label>
                  <select name="documento" class="form-control" required>
                    <option value="" hidden selected disabled>Seleccione una opción</option>
                    <option value="NIF">NIF</option>
                    <option value="NIE">NIE</option>
                    <option value="pasaporte">Pasaporte</option>
                  </select>
                </div>
              </div>  
              <div class="col-sm-12 col-md-6 col-xl-6 mb-2">
                <label id="labelDNI" for="DNI" class="form-label <?= in_array("DNI",$errores) ? "red" : ""?>"><i class="fa-solid fa-id-card <?= in_array("DNI",$errores) ? "red" : ""?>"></i> Número de documentación: *</label>
                  <input type="text" class="form-control" id="DNI" name="DNI" placeholder="Introduzca su DNI..." value="<?= htmlspecialchars($DNI ?? "") ?>" required>
              </div>
            </div>
            
            <div class="row mb-3">
              <div class="col-sm-12 col-md-6 col-xl-6 mb-2">
                <label id="labelNombre" for="nombre" class="form-label <?= in_array("nombre",$errores) ? 'red' : ''?>"><i class="fa-solid fa-user-check <?= in_array("nombre",$errores) ? 'red' : ''?>"></i> Nombre: *</label>
                  <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Introduzca su nombre..." value="<?= htmlspecialchars($nombre ?? "") ?>" required>
              </div>
              <div class="col-sm-12 col-md-6 col-xl-6">
                <label id="labelApellido" for="apellido" class="form-label <?= in_array("apellido",$errores) ? 'red' : ''?>"><i class="fa-solid fa-user-plus <?= in_array("apellido",$errores) ? 'red' : ''?>"></i> Apellido: *</label>
                <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Introduzca su apellido..."  value="<?= htmlspecialchars($apellido ?? "") ?>" required>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-12 col-md-6 col-xl-6 mb-2">
                <label id="labelEmail" for="email" class="form-label <?= in_array("email",$errores) ? 'red' : ''?>"><i class="fa-solid fa-at <?= in_array("email",$errores) ? 'red' : ''?>"></i> Email: *</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Introduzca su email..." value="<?= htmlspecialchars($email ?? "") ?>" required>
              </div>
              <div class="col-sm-12 col-md-6 col-xl-6 mb-2">
                <label id="labelTelefono" for="telefono" class="form-label <?= in_array("telefono",$errores) ? 'red' : ''?>"><i class="fa-solid fa-phone <?= in_array("telefono",$errores) ? 'red' : ''?>"></i> Teléfono: *</label>
                <input type="number" class="form-control" id="telefono" name="telefono" placeholder="--- --- ---" value="<?= htmlspecialchars($telefono ?? "") ?>" required>
              </div>
            </div>
            
            <div class="row">
              
              <div class="col-sm-12 col-md-6 col-xl-6 mb-2">
                <div class="form-group">
                  <label id="labelDireccion" class="form-label<?= in_array("direccion",$errores) ? 'red' : ''?>" for="direccion"><i class="fa-solid fa-map <?= in_array("direccion",$errores) ? 'red' : ''?>"></i> Dirección: *</label>
                  <input class="form-control" type="text" name="direccion" id="direccion" placeholder="Ej: C: --- , Nº- , Piso - , Puerta -  ..." required>
                </div>
              </div>

              <div class="col-sm-12 col-md-6 col-xl-6 mb-2 pb-3">
                <div class="form-group">
                  <label id="labelNacimiento" class="form-label <?= in_array("nacimiento",$errores) ? 'red' : ''?>" for="nacimiento"><i class="fa-solid fa-calendar <?= in_array("nacimiento",$errores) ? 'red' : ''?>"></i> Fecha de nacimiento: *</label>
                  <input class="form-control" type="date" name="nacimiento" id="nacimiento" required>
                </div>
              </div>

            </div>

            <div class="mb-3">
              <label id="labelInteres" for="interes" class="form-label <?= in_array("interes",$errores) ? 'red' : ''?>">
                <i class="fa-solid fa-comments <?= in_array("interes",$errores) ? 'red' : ''?>"></i> ¿Qué le interesa?
              </label>
              <textarea class="form-control" name="interes" id="interes" placeholder="Diganos que le interesa..."><?= htmlspecialchars($interes ?? "") ?></textarea>
            </div>

            <div class="mb-3">
              <label for="captcha" class="form-label <?= $errorCaptcha ? 'red' : ''?>"><i class="fa-solid fa-robot <?= $errorCaptcha ? 'red' : ''?>"></i> Demuestra que no eres una máquina: ¿Cuánto es <?= $num1 ?> + <?= numero_a_texto($num2) ?> más <?= numero_a_texto($num3) ?>?</label>
              <input type="number" class="form-control" id="captcha" name="captcha" placeholder="Introduzca la suma de los números..." required>
            </div>

            <div class="form-check mb-3">
              <input type="checkbox" class="form-check-input" id="privacidad" name="privacidad" required>
              <label class="form-check-label" for="privacidad">He leído y acepto la<a href="privacidad.php" class="text-decoration-none red"> política de privacidad.</a></label>
            </div>

            <div class="text-center">
              <button type="submit" name="send" class="btnEnviarFormulario">Enviar</button>
              <button type="reset" name="reset" class="btnBorrarFormulario">Borrar</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</main>