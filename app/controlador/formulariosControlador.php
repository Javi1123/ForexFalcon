<?php

namespace App\controlador;

use \App\modelo\loginModelo;

class formulariosControlador{
  
  public function acciones() {
    $tipo = $_GET["tipo"];

    // Mínimo 18 años
    $maxFecha = date('Y-m-d', strtotime('-18 years'));
    // Máximo 120 años atrás
    $minFecha = date('Y-m-d', strtotime('-120 years'));
    
    $paises = [
      "Afghanistan","Albania","Algeria","Andorra","Angola","Antigua and Barbuda",
      "Argentina","Armenia","Australia","Austria","Azerbaijan","Bahamas","Bahrain",
      "Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bhutan","Bolivia",
      "Bosnia and Herzegovina","Botswana","Brazil","Brunei","Bulgaria","Burkina Faso",
      "Burundi","Cabo Verde","Cambodia","Cameroon","Canada","Central African Republic",
      "Chad","Chile","China","Colombia","Comoros","Congo (Congo-Brazzaville)",
      "Costa Rica","Croatia","Cuba","Cyprus","Czechia","Denmark","Djibouti","Dominica",
      "Dominican Republic","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea",
      "Estonia","Eswatini","Ethiopia","Fiji","Finland","France","Gabon","Gambia",
      "Georgia","Germany","Ghana","Greece","Grenada","Guatemala","Guinea",
      "Guinea-Bissau","Guyana","Haiti","Honduras","Hungary","Iceland","India",
      "Indonesia","Iran","Iraq","Ireland","Israel","Italy","Jamaica","Japan","Jordan",
      "Kazakhstan","Kenya","Kiribati","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon",
      "Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Madagascar",
      "Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Mauritania",
      "Mauritius","Mexico","Micronesia","Moldova","Monaco","Mongolia","Montenegro",
      "Morocco","Mozambique","Myanmar","Namibia","Nauru","Nepal","Netherlands",
      "New Zealand","Nicaragua","Niger","Nigeria","North Korea","North Macedonia",
      "Norway","Oman","Pakistan","Palau","Palestine","Panama","Papua New Guinea",
      "Paraguay","Peru","Philippines","Poland","Portugal","Qatar","Romania","Russia",
      "Rwanda","Saint Kitts and Nevis","Saint Lucia","Saint Vincent and the Grenadines",
      "Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Serbia",
      "Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands",
      "Somalia","South Africa","South Korea","South Sudan","Spain","Sri Lanka","Sudan",
      "Suriname","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania",
      "Thailand","Timor-Leste","Togo","Tonga","Trinidad and Tobago","Tunisia","Turkey",
      "Turkmenistan","Tuvalu","Uganda","Ukraine","United Arab Emirates","United Kingdom",
      "United States","Uruguay","Uzbekistan","Vanuatu","Vatican City","Venezuela",
      "Vietnam","Yemen","Zambia","Zimbabwe"
    ];
    require __DIR__ . '/../vista/login_vista.php';
  }

  public function inicio(){
    if(!isset($_GET['tipo'])){
      header("Location: " . BASE_PATH);
      exit();
    }

    $tipo = $_GET["tipo"];

    $usuario = $_REQUEST['usuario'] ?? "";
    $contraseña = $_REQUEST['contraseña'] ?? "";
    $errores = [];

    if($_SERVER['REQUEST_METHOD'] === 'POST'){

      if($usuario == ""){
        $errores['usuario'] = true;
      }

      if($contraseña == ""){
        $errores['contraseña'] = true;
      }

      if(empty($errores)){
        
        $usuarioBase = loginModelo::getUsuario($usuario);
        
        if($usuarioBase && hash('sha256', $contraseña) === $usuarioBase['contrasena']){
          $_SESSION['usuario'] = $usuarioBase['usuario'];
          $_SESSION['nombre'] = $usuarioBase['nombre'];
          $_SESSION['apellido'] = $usuarioBase['apellido'];
          header("Location: " . BASE_PATH . '/recursos');
          exit();
        }else{
          $errores['login'] = true;
        }
      }
    }

    require __DIR__ . '/../vista/login_vista.php';
  }
    
  public function crear_cuenta(){
    if(!isset($_GET['tipo'])){
      header("Location: " . BASE_PATH);
      exit();
    }

    $usuario = $_REQUEST['usuario'] ?? "";
    $contraseña = $_REQUEST['contraseña'] ?? "";
    $errores = [];

    if($_SERVER['REQUEST_METHOD'] === 'POST'){

      if($usuario == ""){
        $errores['usuario'] = true;
      }

      if($contraseña == ""){
        $errores['contraseña'] = true;
      }

      if(empty($errores)){
        
        $usuarioBase = loginModelo::getUsuario($usuario);
        
        if($usuarioBase && hash('sha256', $contraseña) === $usuarioBase['contrasena']){
          $_SESSION['usuario'] = $usuarioBase['usuario'];
          $_SESSION['nombre_completo'] = $usuarioBase['nombre_completo'];
          header("Location: " . BASE_PATH . '/admin');
          exit();
        }else{
          $errores['login'] = true;
        }
      }
    }

    $tipo = $_GET["tipo"];

    require __DIR__ . '/../vista/login_vista.php';
  }

  public function logout(){
    session_unset();
    session_destroy();
    header("Location: " . BASE_PATH . '/acciones');
    exit();
  }

}
