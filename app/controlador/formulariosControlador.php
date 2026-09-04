<?php

namespace App\controlador;

use \App\modelo\loginModelo;

class formulariosControlador{
  
  public function acciones() {
    $tipo = $_GET["tipo"];

    if($tipo === 'registro') {
      $this->crear_cuenta();
    }else if($tipo === 'inicio'){
      $this->inicio();
    }
  }

  public function inicio(){
    $tipo = $_GET["tipo"];
    $maxFecha = date('Y-m-d', strtotime('-18 years'));
    $minFecha = date('Y-m-d', strtotime('-120 years'));
    $paises = ["Afghanistan","Albania","Algeria","Andorra","Angola","Antigua and Barbuda","Argentina","Armenia","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bhutan","Bolivia","Bosnia and Herzegovina","Botswana","Brazil","Brunei","Bulgaria","Burkina Faso","Burundi","Cabo Verde","Cambodia","Cameroon","Canada","Central African Republic","Chad","Chile","China","Colombia","Comoros","Congo (Congo-Brazzaville)","Costa Rica","Croatia","Cuba","Cyprus","Czechia","Denmark","Djibouti","Dominica","Dominican Republic","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Eswatini","Ethiopia","Fiji","Finland","France","Gabon","Gambia","Georgia","Germany","Ghana","Greece","Grenada","Guatemala","Guinea","Guinea-Bissau","Guyana","Haiti","Honduras","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland","Israel","Italy","Jamaica","Japan","Jordan","Kazakhstan","Kenya","Kiribati","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Mauritania","Mauritius","Mexico","Micronesia","Moldova","Monaco","Mongolia","Montenegro","Morocco","Mozambique","Myanmar","Namibia","Nauru","Nepal","Netherlands","New Zealand","Nicaragua","Niger","Nigeria","North Korea","North Macedonia","Norway","Oman","Pakistan","Palau","Palestine","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal","Qatar","Romania","Russia","Rwanda","Saint Kitts and Nevis","Saint Lucia","Saint Vincent and the Grenadines","Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Korea","South Sudan","Spain","Sri Lanka","Sudan","Suriname","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","Timor-Leste","Togo","Tonga","Trinidad and Tobago","Tunisia","Turkey","Turkmenistan","Tuvalu","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States","Uruguay","Uzbekistan","Vanuatu","Vatican City","Venezuela","Vietnam","Yemen","Zambia","Zimbabwe"];

    $errorLogin = "";

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $email = $_POST['email_login'] ?? "";
      $contraseña = $_POST['contraseña_login'] ?? "";

      $usuario_base = loginModelo::getUsuario($email);

      if($usuario_base && hash('sha256', $contraseña) === $usuario_base['contraseña']){
        $_SESSION['email'] = $usuario_base['Correo'];
        $_SESSION['nombre'] = $usuario_base['Nombre'];
        $_SESSION['apellido'] = $usuario_base['Apellido'];

        header("Location: " . BASE_PATH . '/recursos');
        exit();
      } else {
        $errorLogin = "El correo o la contraseña son incorrectos.";
      }
    }

    require __DIR__ . '/../vista/login_vista.php';
  }
    
  public function crear_cuenta(){
    $tipo = $_GET["tipo"];
    $maxFecha = date('Y-m-d', strtotime('-18 years'));
    $minFecha = date('Y-m-d', strtotime('-120 years'));
    $paises = ["Afghanistan","Albania","Algeria","Andorra","Angola","Antigua and Barbuda","Argentina","Armenia","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bhutan","Bolivia","Bosnia and Herzegovina","Botswana","Brazil","Brunei","Bulgaria","Burkina Faso","Burundi","Cabo Verde","Cambodia","Cameroon","Canada","Central African Republic","Chad","Chile","China","Colombia","Comoros","Congo (Congo-Brazzaville)","Costa Rica","Croatia","Cuba","Cyprus","Czechia","Denmark","Djibouti","Dominica","Dominican Republic","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Eswatini","Ethiopia","Fiji","Finland","France","Gabon","Gambia","Georgia","Germany","Ghana","Greece","Grenada","Guatemala","Guinea","Guinea-Bissau","Guyana","Haiti","Honduras","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland","Israel","Italy","Jamaica","Japan","Jordan","Kazakhstan","Kenya","Kiribati","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Mauritania","Mauritius","Mexico","Micronesia","Moldova","Monaco","Mongolia","Montenegro","Morocco","Mozambique","Myanmar","Namibia","Nauru","Nepal","Netherlands","New Zealand","Nicaragua","Niger","Nigeria","North Korea","North Macedonia","Norway","Oman","Pakistan","Palau","Palestine","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal","Qatar","Romania","Russia","Rwanda","Saint Kitts and Nevis","Saint Lucia","Saint Vincent and the Grenadines","Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Korea","South Sudan","Spain","Sri Lanka","Sudan","Suriname","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","Timor-Leste","Togo","Tonga","Trinidad and Tobago","Tunisia","Turkey","Turkmenistan","Tuvalu","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States","Uruguay","Uzbekistan","Vanuatu","Vatican City","Venezuela","Vietnam","Yemen","Zambia","Zimbabwe"];

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $nombre_registro = $_POST['nombre_registro'] ?? "";
      $apellido_registro = $_POST['apellido_registro'] ?? "";
      $email_registro = $_POST['email_registro'] ?? "";
      $telefono_registro = $_POST['telefono_registro'] ?? "";
      $pais_registro = $_POST['pais_registro'] ?? "";
      $fecha_nacimiento_registro = $_POST['fecha_nacimiento_registro'] ?? "";
      $contraseña_registro = $_POST['contraseña_registro'] ?? "";

      $usuario_encontrado = loginModelo::getUsuario($email_registro);

      if ($usuario_encontrado) {
        // Ya existe -> no insertamos, solo notificamos
        $_SESSION['toast'] = [
          'tipo'     => 'error',
          'mensaje'  => 'Ya existe una cuenta registrada con este correo electrónico.'
        ];
        header("Location: " . BASE_PATH . "/acciones?tipo=registro");
        exit;
      } else {
        
        $nombre_email = $nombre_registro . " " . $apellido_registro;
        try {
          loginModelo::correo_registro($nombre_email, $email_registro); // ver si funciona con el nuevo xampp
        } catch (\Throwable $th) {
          throw $th;
        }

        // No existe -> creamos la cuenta
        loginModelo::setUsuario(
          $nombre_registro,
          $apellido_registro,
          $email_registro,
          $telefono_registro,
          $pais_registro,
          $fecha_nacimiento_registro,
          $contraseña_registro
        );

        $_SESSION['toast'] = [
          'tipo'    => 'success',
          'mensaje' => 'Gracias por reguistrarte .Ya puedes iniciar sesion.'
        ];

        header("Location: " . BASE_PATH . "/acciones?tipo=inicio");
        exit;
      }
    }

    require __DIR__ . '/../vista/login_vista.php';
  }

  public function logout(){
    session_unset();
    session_destroy();
    header("Location: " . BASE_PATH . '/');
    exit();
  }

}
