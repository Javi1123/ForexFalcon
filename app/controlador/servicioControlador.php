<?php

namespace App\controlador;

use App\core\AlmacenVideos;
use App\modelo\adminServiciosModelo;
use App\modelo\suscripcionModelo;
use App\modelo\videoModelo;

/**
 * GET /servicio?id=AP  →  página de un servicio contratado (a la que llevan los enlaces de la barra lateral).
 *
 * Qué muestra depende del Tipo_servicio:  video → reproductor + lista de vídeos ·  imagenes → (próximamente)
 *  · chat → botón para abrir el chat con el equipo.
 *
 * Acceso: un usuario solo entra si tiene una suscripción vigente a ese servicio. Un administrador puede abrir
 * cualquier servicio (vista previa de lo que ven los usuarios).
 */
class servicioControlador {

  public function ver(): void {
    if (!isset($_SESSION['email'])) {
      header('Location: ' . BASE_PATH . '/acciones?tipo=inicio');
      exit;
    }

    $esAdmin = chatControlador::esAdmin();
    $codigo = strtoupper(isset($_GET['id']) && is_string($_GET['id']) ? trim($_GET['id']) : '');

    $servicio = null;
    if (preg_match('/^[A-Z0-9]{1,20}$/', $codigo)) {
      $servicio = $esAdmin
        ? (adminServiciosModelo::porCodigo($codigo) ?: null)
        : suscripcionModelo::servicioConAcceso($_SESSION['email'], $codigo);
    }

    if (!$servicio) {
      $_SESSION['toast'] = ['tipo' => 'error', 'mensaje' => 'No tienes una suscripción activa a ese servicio.'];
      header('Location: ' . BASE_PATH . '/recursos');
      exit;
    }

    // Vídeos del servicio (solo los que tienen su archivo en el disco)
    $videos = [];
    if ($servicio['Tipo_servicio'] === 'video') {
      foreach (videoModelo::porServicio($servicio['Id_servicio']) as $v) {
        if (AlmacenVideos::rutaVideo($v['Video_url']) !== null) {
          $videos[] = $v;
        }
      }
    }

    // Variables que usan menu.php y cabecera.php
    $inicial = mb_strtoupper(mb_substr($_SESSION['nombre'], 0, 1));
    $colorAvatar = '#' . substr(md5($_SESSION['nombre'] . $_SESSION['apellido']), 0, 6);
    $vistaServicio = true;
    $servicioActivo = $servicio['Id_servicio'];   // para marcarlo en la barra lateral
    $vistaPreviaAdmin = $esAdmin;

    require_once __DIR__ . '/../../app/vista/servicio_vista.php';
  }
}
