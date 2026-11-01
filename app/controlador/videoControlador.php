<?php

namespace App\controlador;

use App\core\AlmacenVideos;
use App\modelo\suscripcionModelo;
use App\modelo\videoModelo;

/**
 * GET /video/ver?id=N  →  entrega el archivo de vídeo SOLO si quien lo pide tiene derecho a verlo:
 *   · un administrador, o
 *   · un usuario con suscripción vigente al servicio al que pertenece el vídeo (y el servicio es de tipo vídeo).
 *
 * Los archivos viven en un almacén privado (ver AlmacenVideos): no existe ninguna dirección pública que
 * se pueda compartir o adivinar. Se admite la cabecera Range (206 Partial Content), imprescindible para
 * que el navegador pueda adelantar y retroceder en el vídeo sin descargarlo entero.
 */
class videoControlador {

  public function ver(): void {
    $correo = $_SESSION['email'] ?? null;
    if (!$correo) {
      self::fin(401, 'Inicia sesión para ver este vídeo.');
    }

    $id = (isset($_GET['id']) && is_string($_GET['id']) && preg_match('/^\d{1,18}$/', $_GET['id'])) ? (int) $_GET['id'] : 0;
    $video = $id > 0 ? videoModelo::porId($id) : false;
    if (!$video) {
      self::fin(404, 'Vídeo no encontrado.');
    }

    if (!chatControlador::esAdmin()) {
      if ($video['Tipo_servicio'] !== 'video' || !suscripcionModelo::tieneAcceso($correo, $video['Id_servicios'])) {
        self::fin(403, 'Necesitas una suscripción activa a este servicio para ver el vídeo.');
      }
    }

    $ruta = AlmacenVideos::rutaVideo($video['Video_url']);
    if ($ruta === null) {
      self::fin(404, 'El archivo de este vídeo no está disponible.');
    }

    // Importante: PHP bloquea la sesión mientras la petición la tiene abierta. Sin esto, un vídeo reproduciéndose
    // (que dura minutos) dejaría al usuario sin poder cargar ninguna otra página de la web.
    session_write_close();

    $tamano = (int) filesize($ruta);
    $inicio = 0;
    $fin = $tamano - 1;
    $codigo = 200;

    // Rango: "bytes=a-b", "bytes=a-" o "bytes=-n" (los últimos n bytes). Cualquier otra forma (varios rangos,
    // otras unidades) se ignora y se sirve el archivo entero, como permite el estándar.
    $rango = isset($_SERVER['HTTP_RANGE']) ? trim($_SERVER['HTTP_RANGE']) : '';
    if ($rango !== '' && preg_match('/^bytes=(\d*)-(\d*)$/', $rango, $m) && ($m[1] !== '' || $m[2] !== '')) {
      if ($m[1] === '') {
        $ultimos = (int) $m[2];
        if ($ultimos === 0) {
          self::rangoNoValido($tamano);
        }
        $inicio = max(0, $tamano - $ultimos);
      } else {
        $inicio = (int) $m[1];
        $fin = ($m[2] === '') ? $tamano - 1 : min((int) $m[2], $tamano - 1);
        if ($inicio >= $tamano || $inicio > $fin) {
          self::rangoNoValido($tamano);
        }
      }
      $codigo = 206;
    }

    @ini_set('zlib.output_compression', '0');
    while (ob_get_level() > 0) {
      ob_end_clean();
    }

    http_response_code($codigo);
    header('Content-Type: ' . AlmacenVideos::tipoMime($video['Video_url']));
    header('Accept-Ranges: bytes');
    header('Content-Length: ' . ($fin - $inicio + 1));
    if ($codigo === 206) {
      header("Content-Range: bytes {$inicio}-{$fin}/{$tamano}");
    }
    header('Content-Disposition: inline');
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('X-Content-Type-Options: nosniff');

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'HEAD') {
      exit;
    }

    set_time_limit(0);
    $f = fopen($ruta, 'rb');
    fseek($f, $inicio);
    $restante = $fin - $inicio + 1;
    while ($restante > 0 && !feof($f) && !connection_aborted()) {
      $datos = fread($f, (int) min(262144, $restante));
      if ($datos === false || $datos === '') {
        break;
      }
      echo $datos;
      $restante -= strlen($datos);
      flush();
    }
    fclose($f);
    exit;
  }

  private static function rangoNoValido(int $tamano): void {
    http_response_code(416);
    header("Content-Range: bytes */{$tamano}");
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Rango no válido.';
    exit;
  }

  /** Error en texto plano (esto lo consume un <video>, no una página). */
  private static function fin(int $codigo, string $mensaje): void {
    http_response_code($codigo);
    header('Content-Type: text/plain; charset=utf-8');
    header('Cache-Control: no-store');
    echo $mensaje;
    exit;
  }
}
