<?php

namespace App\core;

/**
 * Almacén PRIVADO de vídeos.
 *
 * Los vídeos NO se guardan en public/: eso los dejaría accesibles por su dirección para cualquiera, esté
 * suscrito o no. Se guardan en storage/videos/ (con un .htaccess que prohíbe el acceso directo desde el
 * navegador) y solo se sirven a través de /video/ver, que antes comprueba la suscripción.
 *
 * Opcional, en index.php:
 *   define('VIDEOS_DIR', '/ruta/fuera/de/la/web/storage');  // lo ideal en producción: fuera de la carpeta pública
 *   define('VIDEO_MAX_BYTES', 2 * 1024 * 1024 * 1024);      // tamaño máximo por vídeo (por defecto 2 GB)
 *
 * Subida por TROZOS: PHP limita lo que se puede subir de una vez (upload_max_filesize, a menudo 2 MB;
 * y post_max_size). Para que se puedan subir vídeos grandes sin tocar php.ini, el navegador corta el
 * archivo en trozos que caben en ese límite y el servidor los va guardando y, al llegar el último, los une.
 */
class AlmacenVideos {

  /** extensión => tipo MIME. Solo formatos que reproducen los navegadores. (.mov de iPhone: exportar a .mp4) */
  public const EXTENSIONES = ['mp4' => 'video/mp4', 'webm' => 'video/webm', 'ogv' => 'video/ogg'];

  // ───────────── Rutas y límites ─────────────

  public static function raiz(): string {
    return defined('VIDEOS_DIR') ? rtrim(VIDEOS_DIR, '/\\') : dirname(__DIR__, 2) . '/storage';
  }

  public static function dirVideos(): string { return self::raiz() . '/videos'; }
  public static function dirTmp(): string { return self::raiz() . '/tmp'; }

  public static function maxBytes(): int {
    return defined('VIDEO_MAX_BYTES') ? (int) VIDEO_MAX_BYTES : 2 * 1024 * 1024 * 1024;
  }

  /** "40M" / "2G" / "512K" / "1048576" → bytes */
  public static function aBytes(string $valor): int {
    $valor = trim($valor);
    if ($valor === '') {
      return 0;
    }
    $n = (float) $valor;
    switch (strtolower(substr($valor, -1))) {
      case 'g': $n *= 1024;   // no break
      case 'm': $n *= 1024;   // no break
      case 'k': $n *= 1024;
    }
    return (int) $n;
  }

  /**
   * Tamaño de trozo que el servidor admite: el menor de upload_max_filesize y post_max_size, con margen
   * (el resto de campos del formulario también ocupan), y como mucho 8 MB. 0 = el servidor no admite subidas.
   */
  public static function maxTrozo(): int {
    $limites = array_filter([
      self::aBytes((string) ini_get('upload_max_filesize')),
      self::aBytes((string) ini_get('post_max_size')),
    ], function ($v) { return $v > 0; });

    if (!$limites) {
      return 8 * 1024 * 1024;           // sin límite configurado
    }
    return (int) min(8 * 1024 * 1024, floor(min($limites) * 0.9));
  }

  /** Crea las carpetas y las protege. Es seguro llamarla en cada subida. */
  public static function asegurarDirectorios(): void {
    foreach ([self::raiz(), self::dirVideos(), self::dirTmp()] as $dir) {
      if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
        throw new \RuntimeException('No se pudo crear la carpeta de vídeos. Revisa los permisos de ' . $dir);
      }
    }
    if (!is_writable(self::dirVideos()) || !is_writable(self::dirTmp())) {
      throw new \RuntimeException('La carpeta de vídeos no tiene permisos de escritura: ' . self::raiz());
    }

    $htaccess = self::raiz() . '/.htaccess';
    if (!is_file($htaccess)) {
      @file_put_contents($htaccess,
        "# Los vídeos solo se sirven a través de /video/ver (que comprueba la suscripción).\n" .
        "<IfModule mod_authz_core.c>\n  Require all denied\n</IfModule>\n" .
        "<IfModule !mod_authz_core.c>\n  Deny from all\n</IfModule>\n");
    }
  }

  // ───────────── Validación ─────────────

  /** Extensión permitida (en minúsculas) del nombre original del archivo, o null. */
  public static function extensionValida(string $nombreOriginal): ?string {
    $ext = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
    return isset(self::EXTENSIONES[$ext]) ? $ext : null;
  }

  /** ¿Empieza el archivo como un vídeo de ese formato? (no basta fiarse de la extensión) */
  public static function cabeceraValida(string $ruta, string $ext): bool {
    $f = @fopen($ruta, 'rb');
    if (!$f) {
      return false;
    }
    $cab = (string) fread($f, 16);
    fclose($f);

    switch ($ext) {
      case 'mp4':  return strlen($cab) >= 12 && substr($cab, 4, 4) === 'ftyp';
      case 'webm': return strlen($cab) >= 4 && substr($cab, 0, 4) === "\x1A\x45\xDF\xA3";
      case 'ogv':  return strlen($cab) >= 4 && substr($cab, 0, 4) === 'OggS';
    }
    return false;
  }

  public static function idSubidaValido(string $id): bool {
    return (bool) preg_match('/^[a-f0-9]{32}$/', $id);
  }

  // ───────────── Archivos de vídeo ya guardados ─────────────

  /**
   * Ruta real de un vídeo guardado, o null si el nombre no es válido / no existe.
   * El nombre viene de la base de datos pero se vuelve a validar: nunca se concatena sin comprobar.
   */
  public static function rutaVideo(string $archivo): ?string {
    if (!preg_match('/^[a-f0-9]{32}\.(mp4|webm|ogv)$/', $archivo)) {
      return null;
    }
    $ruta = self::dirVideos() . '/' . $archivo;
    $real = realpath($ruta);
    $base = realpath(self::dirVideos());
    if ($real === false || $base === false || strpos($real, $base . DIRECTORY_SEPARATOR) !== 0 || !is_file($real)) {
      return null;
    }
    return $real;
  }

  public static function tipoMime(string $archivo): string {
    return self::EXTENSIONES[strtolower(pathinfo($archivo, PATHINFO_EXTENSION))] ?? 'application/octet-stream';
  }

  public static function tamano(string $archivo): ?int {
    $ruta = self::rutaVideo($archivo);
    return $ruta ? (int) filesize($ruta) : null;
  }

  public static function eliminar(string $archivo): void {
    $ruta = self::rutaVideo($archivo);
    if ($ruta) {
      @unlink($ruta);
    }
  }

  // ───────────── Subida por trozos ─────────────

  private static function dirSubida(string $id): string {
    return self::dirTmp() . '/' . $id;
  }

  /** Datos de la subida (servicio, extensión, nº de trozos, tamaño). null si todavía no existe. */
  public static function leerMeta(string $id): ?array {
    $ruta = self::dirSubida($id) . '/meta.json';
    if (!is_file($ruta)) {
      return null;
    }
    $meta = json_decode((string) file_get_contents($ruta), true);
    return is_array($meta) ? $meta : null;
  }

  public static function escribirMeta(string $id, array $meta): void {
    $dir = self::dirSubida($id);
    if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
      throw new \RuntimeException('No se pudo crear la carpeta temporal de la subida.');
    }
    file_put_contents($dir . '/meta.json', json_encode($meta));
  }

  /** Guarda un trozo recibido por POST. Devuelve false si PHP no lo reconoce como una subida válida. */
  public static function guardarTrozo(string $id, int $indice, string $tmpPhp): bool {
    $dir = self::dirSubida($id);
    if (!is_dir($dir)) {
      return false;
    }
    return move_uploaded_file($tmpPhp, sprintf('%s/p%06d.part', $dir, $indice));
  }

  public static function trozosPresentes(string $id, int $total): bool {
    for ($i = 0; $i < $total; $i++) {
      if (!is_file(sprintf('%s/p%06d.part', self::dirSubida($id), $i))) {
        return false;
      }
    }
    return true;
  }

  /**
   * Une los trozos, comprueba tamaño y formato y deja el vídeo en storage/videos/ con un nombre aleatorio.
   * Devuelve el nombre del archivo. Lanza \RuntimeException con un mensaje para el administrador si algo no cuadra.
   */
  public static function ensamblar(string $id, int $total, string $ext, int $tamanoEsperado): string {
    $dir = self::dirSubida($id);
    $unido = $dir . '/unido.bin';

    try {
      $out = @fopen($unido, 'wb');
      if (!$out) {
        throw new \RuntimeException('No se pudo escribir el vídeo en el servidor.');
      }
      $suma = 0;
      for ($i = 0; $i < $total; $i++) {
        $in = @fopen(sprintf('%s/p%06d.part', $dir, $i), 'rb');
        if (!$in) {
          fclose($out);
          throw new \RuntimeException('Falta un trozo del vídeo. Vuelve a subirlo.');
        }
        $suma += (int) stream_copy_to_stream($in, $out);
        fclose($in);
        if ($suma > self::maxBytes()) {
          fclose($out);
          throw new \RuntimeException('El vídeo supera el tamaño máximo permitido.');
        }
      }
      fclose($out);

      if ($suma !== $tamanoEsperado) {
        throw new \RuntimeException('El vídeo llegó incompleto o dañado. Vuelve a subirlo.');
      }
      if (!self::cabeceraValida($unido, $ext)) {
        throw new \RuntimeException("El archivo no es un vídeo .{$ext} válido.");
      }

      $nombre = bin2hex(random_bytes(16)) . '.' . $ext;
      if (!@rename($unido, self::dirVideos() . '/' . $nombre)) {
        throw new \RuntimeException('No se pudo guardar el vídeo en la carpeta de destino.');
      }
      return $nombre;
    } finally {
      self::descartar($id);
    }
  }

  /** Borra la carpeta temporal de una subida (trozos + datos). */
  public static function descartar(string $id): void {
    if (!self::idSubidaValido($id)) {
      return;
    }
    self::borrarCarpeta(self::dirSubida($id));
  }

  /** Quita restos de subidas abandonadas (por defecto, de más de 24 horas). */
  public static function limpiarTemporales(int $maxEdad = 86400): void {
    $base = self::dirTmp();
    if (!is_dir($base)) {
      return;
    }
    foreach (scandir($base) as $nombre) {
      if (!self::idSubidaValido($nombre)) {
        continue;
      }
      $dir = $base . '/' . $nombre;
      if (is_dir($dir) && (time() - filemtime($dir)) > $maxEdad) {
        self::borrarCarpeta($dir);
      }
    }
  }

  private static function borrarCarpeta(string $dir): void {
    if (!is_dir($dir)) {
      return;
    }
    foreach (scandir($dir) as $f) {
      if ($f !== '.' && $f !== '..') {
        @unlink($dir . '/' . $f);
      }
    }
    @rmdir($dir);
  }
}
