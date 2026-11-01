<?php

namespace App\modelo;

use App\core\DB;

/**
 * Tabla Videos: un registro por vídeo subido. Cada vídeo pertenece a un servicio (Id_servicios).
 * En Video_url se guarda el NOMBRE DEL ARCHIVO dentro del almacén privado (ver App\core\AlmacenVideos),
 * no una dirección pública: los vídeos solo se ven a través de /video/ver, que comprueba la suscripción.
 */
class videoModelo {

  /** Vídeos de un servicio, del más antiguo al más reciente. */
  public static function porServicio(string $idServicio): array {
    $stmt = DB::getInstance()->prepare(
      "SELECT Id, Nombre_video, Duracion_segundos, Video_url, Fecha_subida
       FROM Videos WHERE Id_servicios = :id ORDER BY Fecha_subida ASC, Id ASC"
    );
    $stmt->execute([':id' => $idServicio]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  /** Un vídeo con el tipo y nombre de su servicio (para decidir si se puede ver). */
  public static function porId(int $id) {
    $stmt = DB::getInstance()->prepare(
      "SELECT v.Id, v.Nombre_video, v.Duracion_segundos, v.Video_url, v.Fecha_subida, v.Id_servicios,
              sv.Tipo_servicio, sv.Nombre_servicio
       FROM Videos v JOIN Servicios sv ON sv.Id_servicio = v.Id_servicios
       WHERE v.Id = :id"
    );
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  /** código de servicio (en mayúsculas) => nº de vídeos */
  public static function contarPorServicio(): array {
    $filas = DB::getInstance()->query("SELECT Id_servicios, COUNT(*) AS n FROM Videos GROUP BY Id_servicios")->fetchAll(\PDO::FETCH_ASSOC);
    $mapa = [];
    foreach ($filas as $f) {
      $mapa[strtoupper($f['Id_servicios'])] = (int) $f['n'];
    }
    return $mapa;
  }

  public static function crear(string $idServicio, string $nombre, int $duracion, string $archivo): int {
    $pdo = DB::getInstance();
    $stmt = $pdo->prepare(
      "INSERT INTO Videos (Nombre_video, Duracion_segundos, Video_url, Id_servicios)
       VALUES (:nombre, :duracion, :archivo, :servicio)"
    );
    $stmt->execute([':nombre' => $nombre, ':duracion' => $duracion, ':archivo' => $archivo, ':servicio' => $idServicio]);
    return (int) $pdo->lastInsertId();
  }

  public static function renombrar(int $id, string $nombre): bool {
    $stmt = DB::getInstance()->prepare("UPDATE Videos SET Nombre_video = :nombre WHERE Id = :id");
    $stmt->execute([':nombre' => $nombre, ':id' => $id]);
    return $stmt->rowCount() > 0;
  }

  /** Borra el registro y devuelve el nombre del archivo para que quien llama lo elimine del disco. */
  public static function eliminar(int $id): ?string {
    $pdo = DB::getInstance();
    $stmt = $pdo->prepare("SELECT Video_url FROM Videos WHERE Id = :id");
    $stmt->execute([':id' => $id]);
    $archivo = $stmt->fetchColumn();
    if ($archivo === false) {
      return null;
    }
    $del = $pdo->prepare("DELETE FROM Videos WHERE Id = :id");
    $del->execute([':id' => $id]);
    return $archivo;
  }
}
