<?php

namespace App\controlador;

use \App\modelo\tablasModelo;

class tablasControlador{

  ///////////////////////////////////////////////////////////
  // INDEX de tabla sugerencias
  ///////////////////////////////////////////////////////////

  public function indexSugerencias(){
    if(!isset($_SESSION['usuario'])){
      header("Location: " . BASE_PATH . '/login');
      exit();
    }
    $usuario = $_SESSION['usuario'];
    $nombre_completo = $_SESSION['nombre_completo'];
    $sugerencias = tablasModelo::getAllSugerecias();

    require_once __DIR__ . '/../vista/tabla_sugerencias_vista.php';
  }

  ///////////////////////////////////////////////////////////
  // INDEX de tabla Alumnos
  ///////////////////////////////////////////////////////////

  public function indexAlumnos(){
    if(!isset($_SESSION['usuario'])){
      header("Location: " . BASE_PATH . '/login');
      exit();
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST'){

      // UPDATE variables
      $updateDNI = $_REQUEST["updateDNI"] ?? "";
      $updateUsuario = $_REQUEST["updateUsuario"] ?? "";
      $updateCCC = $_REQUEST["updateCCC"] ?? "";
      $updateMatricula = $_REQUEST["updateMatricula"] ?? "";
      
      // DELETE variables
      $deleteDNI = $_REQUEST["deleteDNI"] ?? "";

      if($updateDNI) {
        tablasModelo::updateAlumnos($updateDNI, $updateUsuario, $updateCCC, $updateMatricula);
      }
      
      if($deleteDNI) {
        tablasModelo::deleteAlumnos($deleteDNI);
      }
      
    }

    $usuario = $_SESSION['usuario'];
    $nombre_completo = $_SESSION['nombre_completo'];
    $alumnos = tablasModelo::getAllAlumnos();

    require_once __DIR__ . '/../vista/tabla_alumnos_vista.php';
  }

  ///////////////////////////////////////////////////////////
  // INDEX de tabla cursos
  ///////////////////////////////////////////////////////////

  public function indexCursos(){
    if(!isset($_SESSION['usuario'])){
      header("Location: " . BASE_PATH . '/login');
      exit();
    }
    
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      // INSERT variables
      $clave = $_REQUEST["clave"] ?? "";
      $descripcion = $_REQUEST["descripcion"] ?? "";
      $precioMes = $_REQUEST["precioMes"] ?? "";
      $matricula = $_REQUEST["matricula"] ?? "";
      $baja = $_REQUEST["baja"] ?? "";
      $notas = $_REQUEST["notas"] ?? "";
      
      // UPDATE variables
      $updateClave = $_REQUEST["updateClave"] ?? "";
      $updateDescripcion = $_REQUEST["updateDescripcion"] ?? "";
      $updatePrecioMes = $_REQUEST["updatePrecioMes"] ?? "";
      $updateMatricula = $_REQUEST["updateMatricula"] ?? "";
      $updateBaja = $_REQUEST["updateBaja"] ?? "";
      $updateNotas = $_REQUEST["updateNotas"] ?? "";
      
      // DELETE variables
      $accionesClave = $_REQUEST["accionesClave"] ?? "";
      $accionesBaja = $_REQUEST["accionesBaja"] ?? "";

      if($clave){
        tablasModelo::insertCursos($clave, $descripcion, $precioMes, $matricula, $baja, $notas);
      }
      
        tablasModelo::updateCursos($updateClave, $updateDescripcion, $updatePrecioMes, $updateMatricula, $updateBaja, $updateNotas);

      
      if($accionesClave) {
        tablasModelo::deleteCursos($accionesClave, $accionesBaja);
      }

      
    }
      
    $usuario = $_SESSION['usuario'];
    $nombre_completo = $_SESSION['nombre_completo'];
    $cursos = tablasModelo::getAllCursos();
      
    require_once __DIR__ . '/../vista/tabla_cursos_vista.php';
  }

  ///////////////////////////////////////////////////////////
  // INDEX de tabla recibos
  ///////////////////////////////////////////////////////////

  public function indexRecibos(){
    if(!isset($_SESSION['usuario'])){
      header("Location: " . BASE_PATH . '/login');
      exit();
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      // INSERT variables
      $DNI = $_REQUEST["DNI"] ?? "";
      $DNIVarios = $_REQUEST["DNIs"] ?? "";
      $claveCurso = $_REQUEST["claveCurso"] ?? "";
      $estado = $_REQUEST["estado"] ?? "";
      $cantidad = $_REQUEST["cantidad"] ?? "";

      // UPDATE variables
      $updateID = $_REQUEST["updateID"] ?? "";
      $updateEstado = $_REQUEST["updateEstado"] ?? "";
      $updateCantidad = $_REQUEST["updateCantidad"] ?? "";
      
      // DELETE variables
      $deleteID = $_REQUEST["deleteID"] ?? "";

      if($DNI) {
        tablasModelo::insertRecibos($DNI, $claveCurso, $estado, $cantidad);
      }

      if($DNIVarios) {
        tablasModelo::insertVariosRecibos($DNIVarios, $claveCurso, $estado, $cantidad);
      }

      if($updateID) {
        tablasModelo::updateRecibos($updateID, $updateEstado, $updateCantidad);
      }
      
      if($deleteID) {
        tablasModelo::deleteRecibos($deleteID);
      }
    }

    $DNIs = tablasModelo::getCaracteristicasAlumnos();

    $jsonDNIs = json_encode($DNIs, JSON_PRETTY_PRINT);

    file_put_contents('./public/json/caracteristicasAlumnos.json', $jsonDNIs);

    $usuario = $_SESSION['usuario'];
    $nombre_completo = $_SESSION['nombre_completo'];
    $recibos = tablasModelo::getAllRecibos();

    require_once __DIR__ . '/../vista/tabla_recibos_vista.php';
  }

  ///////////////////////////////////////////////////////////
  // INDEX de tabla matriculados
  ///////////////////////////////////////////////////////////

  public static function indexMatriculados() {
    if(!isset($_SESSION['usuario'])){
      header("Location: " . BASE_PATH . '/login');
      exit();
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      // INSERT variables
      $DNI = $_REQUEST["DNI"] ?? "";
      $claveCurso = $_REQUEST["claveCurso"] ?? "";
      $certificado = $_REQUEST["certificado"] ?? "";
      $alta = $_REQUEST["alta"] ?? "";
      $notas = $_REQUEST["notas"] ?? "";

      // UPDATE variables
      $updateDNI = $_REQUEST["updateDNI"] ?? "";
      $updateClaveCurso = $_REQUEST["updateClaveCurso"] ?? "";
      $updateCertificado = $_REQUEST["updateCertificado"] ?? "";
      $updateAlta = $_REQUEST["updateAlta"] ?? "";
      $updateNotas = $_REQUEST["updateNotas"] ?? "";
      
      // DELETE variables
      $altaDNI = $_REQUEST["altaDNI"] ?? "";
      $altaClaveCurso = $_REQUEST["altaClaveCurso"] ?? "";
      $altaAlta = $_REQUEST["altaAlta"] ?? "";

      if($DNI) {
        tablasModelo::insertMatriculados($DNI, $claveCurso, $certificado, $alta, $notas);
      }

      if($updateDNI) {
        tablasModelo::updateMatriculado($updateDNI, $updateClaveCurso, $updateCertificado, $updateAlta, $updateNotas);
      }
      
      if($altaDNI) {
        tablasModelo::deleteMatriculados($altaDNI, $altaClaveCurso, $altaAlta);
      }
    }
  
    $DNIs = tablasModelo::getCaracteristicasAlumnos();
    $claveCursos = tablasModelo::getClaveCursos();
    
    $jsonDNIs = json_encode($DNIs, JSON_PRETTY_PRINT);
    $jsonClaveCursos = json_encode($claveCursos, JSON_PRETTY_PRINT);

    file_put_contents('./public/json/caracteristicasAlumnos.json', $jsonDNIs);
    file_put_contents('./public/json/claveCursos.json', $jsonClaveCursos);

    $usuario = $_SESSION['usuario'];
    $nombre_completo = $_SESSION['nombre_completo'];
    $matriculados = tablasModelo::getAllMatriculados();


    require_once __DIR__ . '/../vista/tabla_matriculados_vista.php';
  }


}
