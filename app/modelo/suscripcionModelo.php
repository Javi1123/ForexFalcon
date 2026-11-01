<?php

namespace App\modelo;

use App\core\DB;

/**
 * Qué servicios tiene contratados cada usuario (tabla Suscripciones).
 *
 * Una suscripción está VIGENTE si Activado = 1 y (no tiene Fecha_fin, o la Fecha_fin aún no ha llegado).
 * Hoy /guardar siempre guarda Fecha_fin = NULL, así que cuenta como vigente mientras Activado sea 1.
 *
 * Un usuario puede haber comprado el mismo servicio varias veces (varias filas): por eso se agrupa por
 * servicio, para que aparezca una sola vez.
 *
 * Si archivas un servicio en el panel de administración, quien ya lo tenía contratado SIGUE teniendo
 * acceso: lo único que cambia es que deja de venderse.
 */
class suscripcionModelo {

  private const VIGENTE = "s.Activado = 1 AND (s.Fecha_fin IS NULL OR s.Fecha_fin > NOW())";

  /** Servicios con suscripción vigente: [ [Id_servicio, Nombre_servicio, Tipo_servicio], ... ] */
  public static function activasDeUsuario(string $correo): array {
    $stmt = DB::getInstance()->prepare(
      "SELECT sv.Id_servicio, sv.Nombre_servicio, sv.Tipo_servicio
       FROM Suscripciones s
       JOIN Servicios sv ON sv.Id_servicio = s.Id_servicio
       WHERE s.Correo = :correo AND " . self::VIGENTE . "
       GROUP BY sv.Id_servicio, sv.Nombre_servicio, sv.Tipo_servicio
       ORDER BY sv.Nombre_servicio ASC"
    );
    $stmt->execute([':correo' => $correo]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  /** El servicio (con su nombre, descripción y tipo) si el usuario tiene una suscripción vigente; si no, null. */
  public static function servicioConAcceso(string $correo, string $idServicio): ?array {
    $stmt = DB::getInstance()->prepare(
      "SELECT sv.Id_servicio, sv.Nombre_servicio, sv.Descripcion_servicio, sv.Tipo_servicio
       FROM Suscripciones s
       JOIN Servicios sv ON sv.Id_servicio = s.Id_servicio
       WHERE s.Correo = :correo AND sv.Id_servicio = :id AND " . self::VIGENTE . "
       LIMIT 1"
    );
    $stmt->execute([':correo' => $correo, ':id' => $idServicio]);
    $fila = $stmt->fetch(\PDO::FETCH_ASSOC);
    return $fila ?: null;
  }

  public static function tieneAcceso(string $correo, string $idServicio): bool {
    return self::servicioConAcceso($correo, $idServicio) !== null;
  }
}
