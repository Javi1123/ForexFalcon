<!-- ZONA PHP -->
<?php

  use \App\modelo\tablasModelo;

  $errores = [];
  $errorCaptcha = false;

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST['nombre']) ?? "";
    $email = trim($_POST['email']) ?? "";
    $apellido = trim($_POST['apellido']) ?? "";
    $telefono = trim($_POST['telefono']) ?? "";
    $sugerencia = trim($_POST['sugerencia']) ?? "";
    $captcha = trim($_POST["captcha"]) ?? "";
    
    if(is_numeric($captcha)){
      $num1 = $_SESSION["numeros"][0];
      $num2 = $_SESSION["numeros"][1];
      $num3 = $_SESSION["numeros"][2];
    
      $_SESSION["suma"] = $num1 + $num2 + $num3;
  
      if ($captcha != $_SESSION["suma"]) {
        array_push($errores, "captcha");
      }
    }

    if (!validarNombre($nombre))            array_push($errores, "nombre");
    if (!validarEmail($email))              array_push($errores, "email");
    if (!validarApellido($apellido))        array_push($errores, "apellido");
    if (!validarTelefono($telefono))        array_push($errores, "telefono");
    if (!validarSugerencia($sugerencia))    array_push($errores, "sugerencia");
    
    validarSiHayMasErrores($errores); 
    
    if(!$errores){
      
      tablasModelo::insertSugerencias($nombre, $apellido, $email, $telefono, $sugerencia);

      $asunto = $nombre. " mandó una sugerencia y/o reclamacion desde el formulario de Certitrasporte.";

      $c_DatosAlumno = "El usuario a hecho una consulta desde forexfalcon en el formulario de sugerecias y/o reclamaciones.<br><br>";
      $c_DatosAlumno .= "<b>Nombre:</b> ".$nombre. "<br>";
      $c_DatosAlumno .= "<b>Apellido:</b> " .$apellido. "<br>";
      $c_DatosAlumno .= "<b>Correo:</b> " .$email. "<br>";
      $c_DatosAlumno .= "<b>Telefono:</b> " .$telefono. "<br>";
      $c_DatosAlumno .= "<b>Fecha:</b> " .date("F j, Y, g:i a"). "<br>";
      $c_DatosAlumno .= "<b>Sugerencias y/o reclación:</b> " .$sugerencia. "<br>";

      $t_form = "Sugerecias";

      correos_nosotros($asunto ,$c_DatosAlumno, $t_form);

      $_SESSION["popErrores"] = [
        "icon" => "success",
        "titulo" => "Sugerencia enviada correctamente:",
        "texto" => "Gracias, su sugerencia y/o reclamación a sido enviada, pronto nos pondremos en contacto contigo",
      ];
      $nombre = "";
      $email = "";
      $apellido = "";
      $telefono = "";
      $sugerencia = "";
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

<!-- Zona Formulario -->
<main class="container py-4">
  <h2 class="text-center mb-3 negrita"><i class="fa-solid fa-pencil red"></i> Sugerencias y/o reclamaciones</h2>
  
  <div class="container">
    <p class="parrafosDescriptivos negrita orange">Ayúdenos a mejorar con su opinión:</p>
    <p class="parrafosDescriptivos">Nos gustaría conocer <span class="negrita">tu opinión</span> para mejorar. ¿Qué crees que deberíamos cambiar en nuestra forma de trabajar o en el equipo? También nos sugerenciaan tus <span class="negrita">ideas nuevas</span>: herramientas, formas de organizarnos o cualquier propuesta que nos ayude a crecer.</p>
    <p class="parrafosDescriptivos">Háblanos también de las <span class="negrita">clases</span>: qué te ha gustado, qué cambiarías o cómo podríamos hacerlas más útiles y amenas.</p>
    <p class="pb-3 parrafosDescriptivos">Por último, cuéntanos lo que piensas de nuestros servicios. Tus sugerencias y también cualquier cosa que no te haya gustado son bienvenidas. Tu feedback nos ayuda a mejorar cada día.</p>
  </div>

  <div class="row justify-content-center">
    <p class="h2 text-center"><i class="fas fa-edit red"></i> Fomulario de sugerencias</p>
    <div class="card p-3 col-11 col-sm-9 col-md-10 col-xl-10">
      <form id="formulario" action="forexfalcon/sugerencias" method="post">
        <div class="row mb-3">
          <div class="col-sm-12 col-md-6 col-xl-6 mb-2">
            <label id="labelNombre" for="nombre" class="form-label <?= in_array("nombre",$errores) ? 'red' : ''?>"><i class="fa-solid fa-user-check <?= in_array("nombre",$errores) ? 'red' : ''?>" ></i> Nombre: *</label>
            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Introduzca el nombre..." value="<?= htmlspecialchars($nombre ?? "") ?>" required>
          </div>
          <div class="col-sm-12 col-md-6 col-xl-6">
            <label id="labelApellido" for="apellido" class="form-label <?= in_array("apellido",$errores) ? 'red' : ''?>"><i class="fa-solid fa-user-plus <?= in_array("apellido",$errores) ? 'red' : ''?>"></i> Apellido: </label>
            <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Introduzca el apellido..." value="<?= htmlspecialchars($apellido ?? "") ?>">
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-sm-12 col-md-6 col-xl-6 mb-2">
            <label id="labelEmail" for="email" class="form-label <?= in_array("email",$errores) ? 'red' : ''?>"><i class="fa-solid fa-at <?= in_array("email",$errores) ? 'red' : ''?>"></i> Email: </label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Introduzca el email..." value="<?= htmlspecialchars($email ?? "") ?>">
          </div>
          <div class="col-sm-12 col-md-6 col-xl-6">
            <label id="labelTelefono" for="telefono" class="form-label <?= in_array("telefono",$errores) ? 'red' : ''?>"><i class="fa-solid fa-phone <?= in_array("telefono",$errores) ? 'red' : ''?>"></i> Teléfono: </label>
            <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="--- --- ---" value="<?= htmlspecialchars($telefono ?? "") ?>">
          </div>
        </div>

        <div class="mb-3">
          <label id="labelSugerencia" for="sugerencia" class="form-label <?= in_array("sugerencia",$errores) ? 'red' : ''?>">
            <i class="fa-solid fa-comments <?= in_array("sugerencia",$errores) ? 'red' : ''?>"></i> Diganos tu sugerencia y/o reclamación
          </label>
          <textarea class="form-control" name="sugerencia" id="sugerencia" placeholder="Diganos que le sugerencia..."><?= htmlspecialchars($sugerencia ?? "") ?></textarea>
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
</main>