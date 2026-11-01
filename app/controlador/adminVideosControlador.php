<?php

namespace App\controlador;

use App\core\AlmacenVideos;
use App\modelo\adminServiciosModelo;
use App\modelo\videoModelo;

/**
 * Vídeos de un servicio (solo Administrador / Superusuario). Hereda de adminControlador la comprobación de
 * sesión + rol + POST + token CSRF y el formato de respuesta JSON, así que se protege igual que el resto del panel.
 *
 *   GET  /admin/videos?servicio=AP   → lista de vídeos del servicio + formulario de subida
 *   POST /admin/videos/subir         → recibe UN trozo de un vídeo (el último trozo une todo y crea el registro)
 *   POST /admin/videos/cancelar      → descarta una subida a medias
 *   POST /admin/videos/renombrar     → cambia el nombre de un vídeo
 *   POST /admin/videos/eliminar      → borra el registro y el archivo
 */
class adminVideosControlador extends adminControlador {

  private const MAX_DURACION = 43200;   // 12 horas, en segundos

  // ───────────────────────── Vista ─────────────────────────

  public function pagina(): void {
    if (!isset($_SESSION['email'])) {
      header('Location: ' . BASE_PATH . '/acciones?tipo=inicio');
      exit;
    }
    if (!chatControlador::esAdmin()) {
      $_SESSION['toast'] = ['tipo' => 'error', 'mensaje' => 'No tienes permisos para acceder a la administración.'];
      header('Location: ' . BASE_PATH . '/recursos');
      exit;
    }

    $codigo = strtoupper(isset($_GET['servicio']) && is_string($_GET['servicio']) ? trim($_GET['servicio']) : '');
    $servicio = preg_match('/^[A-Z0-9]{1,20}$/', $codigo) ? adminServiciosModelo::porCodigo($codigo) : false;

    if (!$servicio || $servicio['Tipo_servicio'] !== 'video') {
      $_SESSION['toast'] = ['tipo' => 'error', 'mensaje' => $servicio
        ? 'Ese servicio no es de tipo vídeo. Cambia su tipo en «Editar» para poder subir vídeos.'
        : 'Ese servicio no existe.'];
      header('Location: ' . BASE_PATH . '/admin/servicios');
      exit;
    }

    $videos = videoModelo::porServicio($servicio['Id_servicio']);
    foreach ($videos as &$v) {
      $v['bytes'] = AlmacenVideos::tamano($v['Video_url']);   // null = el archivo ya no está en el disco
    }
    unset($v);

    // Variables que usan menu.php y cabecera.php
    $inicial = mb_strtoupper(mb_substr($_SESSION['nombre'], 0, 1));
    $colorAvatar = '#' . substr(md5($_SESSION['nombre'] . $_SESSION['apellido']), 0, 6);
    $vistaAdmin = true;
    $vistaServicio = true;   // carga también servicio.css (el marco 16:9 de la vista previa)

    $config = [
      'base'      => BASE_PATH,
      'csrf'      => self::tokenCsrf(),
      'servicio'  => $servicio['Id_servicio'],
      'maxTrozo'  => AlmacenVideos::maxTrozo(),
      'maxBytes'  => AlmacenVideos::maxBytes(),
      'maxDuracion' => self::MAX_DURACION,
      'extensiones' => array_keys(AlmacenVideos::EXTENSIONES),
    ];

    require_once __DIR__ . '/../../app/vista/admin_videos_vista.php';
  }

  // ───────────────────────── Acciones ─────────────────────────

  public function subir(): void {
    $this->accion(function () {
      // Si el trozo supera post_max_size, PHP descarta TODO el formulario y no llega ni $_POST ni $_FILES.
      if (empty($_POST) && empty($_FILES) && (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
        throw new \InvalidArgumentException('El trozo supera el límite de subida de PHP (post_max_size). Recarga la página e inténtalo de nuevo.');
      }

      AlmacenVideos::asegurarDirectorios();

      $uploadId = $this->texto($_POST, 'upload_id');
      if (!AlmacenVideos::idSubidaValido($uploadId)) {
        throw new \InvalidArgumentException('Identificador de subida no válido.');
      }

      $total  = $this->entero($_POST['total'] ?? null, 1, 1000000, 'Número de trozos no válido.');
      $indice = $this->entero($_POST['indice'] ?? null, 0, $total - 1, 'Número de trozo no válido.');

      $codigo = strtoupper($this->texto($_POST, 'servicio'));
      $servicio = preg_match('/^[A-Z0-9]{1,20}$/', $codigo) ? adminServiciosModelo::porCodigo($codigo) : false;
      if (!$servicio) {
        throw new \InvalidArgumentException('El servicio no existe.');
      }
      if ($servicio['Tipo_servicio'] !== 'video') {
        throw new \InvalidArgumentException('Ese servicio no es de tipo vídeo.');
      }

      $nombre = $this->texto($_POST, 'nombre');
      if ($nombre === '') {
        throw new \InvalidArgumentException('El nombre del vídeo es obligatorio.');
      }
      if (mb_strlen($nombre) > 200) {
        throw new \InvalidArgumentException('El nombre no puede pasar de 200 caracteres.');
      }

      $duracion = $this->entero($_POST['duracion'] ?? null, 1, self::MAX_DURACION, 'La duración debe estar entre 1 segundo y 12 horas.');
      $tamano   = $this->entero($_POST['tamano'] ?? null, 1, PHP_INT_MAX, 'Tamaño del vídeo no válido.');
      if ($tamano > AlmacenVideos::maxBytes()) {
        throw new \InvalidArgumentException('El vídeo supera el tamaño máximo permitido (' . $this->legible(AlmacenVideos::maxBytes()) . ').');
      }

      $ext = AlmacenVideos::extensionValida($this->texto($_POST, 'archivo'));
      if ($ext === null) {
        throw new \InvalidArgumentException('Formato no admitido. Sube un vídeo .mp4, .webm o .ogv.');
      }

      $trozo = $_FILES['chunk'] ?? null;
      if (!$trozo || !is_array($trozo) || ($trozo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new \InvalidArgumentException($this->errorSubida($trozo['error'] ?? UPLOAD_ERR_NO_FILE));
      }
      if ((int) $trozo['size'] <= 0) {
        throw new \InvalidArgumentException('Se recibió un trozo vacío.');
      }

      // Primer trozo: se limpian restos antiguos y se anotan los datos de la subida.
      // Los siguientes tienen que coincidir con ellos (nadie puede cambiar de servicio o formato a mitad).
      $meta = ['servicio' => $servicio['Id_servicio'], 'ext' => $ext, 'total' => $total, 'tamano' => $tamano];
      $previa = AlmacenVideos::leerMeta($uploadId);
      if ($previa === null) {
        AlmacenVideos::limpiarTemporales();
        AlmacenVideos::escribirMeta($uploadId, $meta);
      } elseif ($previa !== $meta) {
        throw new \InvalidArgumentException('Los datos de la subida no coinciden con los del primer trozo.');
      }

      if (!AlmacenVideos::guardarTrozo($uploadId, $indice, $trozo['tmp_name'])) {
        throw new \RuntimeException('No se pudo guardar el trozo en el servidor.');
      }

      if ($indice < $total - 1) {
        return ['recibido' => $indice, 'completo' => false];
      }

      // Último trozo: se unen, se valida y se crea el registro.
      if (!AlmacenVideos::trozosPresentes($uploadId, $total)) {
        throw new \DomainException('Faltan trozos del vídeo. Vuelve a subirlo.');
      }
      try {
        $archivo = AlmacenVideos::ensamblar($uploadId, $total, $ext, $tamano);
      } catch (\RuntimeException $e) {
        throw new \InvalidArgumentException($e->getMessage());
      }

      try {
        $id = videoModelo::crear($servicio['Id_servicio'], $nombre, $duracion, $archivo);
      } catch (\Throwable $e) {
        AlmacenVideos::eliminar($archivo);   // sin registro no debe quedar el archivo huérfano
        throw $e;
      }

      return [
        'recibido' => $indice,
        'completo' => true,
        'video'    => ['id' => $id, 'nombre' => $nombre, 'duracion' => $duracion, 'bytes' => $tamano],
        'mensaje'  => 'Vídeo subido.',
      ];
    });
  }

  public function cancelar(): void {
    $this->accion(function () {
      $uploadId = $this->texto($_POST, 'upload_id');
      if (!AlmacenVideos::idSubidaValido($uploadId)) {
        throw new \InvalidArgumentException('Identificador de subida no válido.');
      }
      AlmacenVideos::descartar($uploadId);
      return ['mensaje' => 'Subida cancelada.'];
    });
  }

  public function renombrar(): void {
    $this->accion(function () {
      $id = $this->entero($_POST['id'] ?? null, 1, PHP_INT_MAX, 'Vídeo no válido.');
      $nombre = $this->texto($_POST, 'nombre');
      if ($nombre === '') {
        throw new \InvalidArgumentException('El nombre es obligatorio.');
      }
      if (mb_strlen($nombre) > 200) {
        throw new \InvalidArgumentException('El nombre no puede pasar de 200 caracteres.');
      }
      if (!videoModelo::porId($id)) {
        throw new \DomainException('Ese vídeo ya no existe.');
      }
      videoModelo::renombrar($id, $nombre);
      return ['mensaje' => 'Nombre actualizado.', 'nombre' => $nombre];
    });
  }

  public function eliminar(): void {
    $this->accion(function () {
      $id = $this->entero($_POST['id'] ?? null, 1, PHP_INT_MAX, 'Vídeo no válido.');
      $archivo = videoModelo::eliminar($id);
      if ($archivo === null) {
        throw new \DomainException('Ese vídeo ya no existe.');
      }
      AlmacenVideos::eliminar($archivo);
      return ['mensaje' => 'Vídeo eliminado.'];
    });
  }

  // ───────────────────────── Ayudas ─────────────────────────

  /** Entero estricto dentro de un rango (rechaza "12abc", "1.5", arrays, vacíos...). */
  private function entero($valor, int $min, int $max, string $mensaje): int {
    if (!is_string($valor) || !preg_match('/^\d{1,18}$/', $valor)) {
      throw new \InvalidArgumentException($mensaje);
    }
    $n = (int) $valor;
    if ($n < $min || $n > $max) {
      throw new \InvalidArgumentException($mensaje);
    }
    return $n;
  }

  private function legible(int $bytes): string {
    return $bytes >= 1073741824 ? round($bytes / 1073741824, 1) . ' GB' : round($bytes / 1048576) . ' MB';
  }

  private function errorSubida(int $codigo): string {
    switch ($codigo) {
      case UPLOAD_ERR_INI_SIZE:
      case UPLOAD_ERR_FORM_SIZE:
        return 'El trozo supera el límite de subida de PHP (upload_max_filesize). Recarga la página e inténtalo de nuevo.';
      case UPLOAD_ERR_PARTIAL:
        return 'El trozo llegó incompleto. Inténtalo de nuevo.';
      case UPLOAD_ERR_NO_FILE:
        return 'No se recibió ningún archivo.';
      case UPLOAD_ERR_NO_TMP_DIR:
      case UPLOAD_ERR_CANT_WRITE:
        return 'El servidor no puede guardar archivos temporales (revisa upload_tmp_dir y los permisos).';
      default:
        return 'Error al recibir el archivo (código ' . $codigo . ').';
    }
  }
}
