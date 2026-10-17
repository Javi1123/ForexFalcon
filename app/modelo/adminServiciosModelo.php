<?php

namespace App\modelo;

use App\core\DB;

/**
 * Tabla Servicios de tu base de datos.
 *
 * ¿Por qué hay que mantenerla sincronizada con Stripe? Porque Suscripciones.Id_servicio es una
 * clave foránea hacia Servicios.Id_servicio. Cuando alguien paga, /guardar inserta la suscripción
 * con el id_servicio del producto de Stripe: si ese código no existe aquí, la inserción falla.
 * Por eso cada alta/edición/archivado del panel toca Stripe Y esta tabla.
 */
class adminServiciosModelo {

  /** código (en mayúsculas) => Activado (0/1) */
  public static function existentes(): array {
    $filas = DB::getInstance()->query("SELECT Id_servicio, Activado FROM Servicios")->fetchAll(\PDO::FETCH_ASSOC);
    $mapa = [];
    foreach ($filas as $f) {
      $mapa[strtoupper($f['Id_servicio'])] = (int) $f['Activado'];
    }
    return $mapa;
  }

  /** Alta de un servicio nuevo. Lanza \DomainException si el código ya existe. */
  public static function insertar(string $codigo, string $nombre, string $descripcion, string $metodo): void {
    $pdo = DB::getInstance();
    try {
      $stmt = $pdo->prepare(
        "INSERT INTO Servicios (Id_servicio, Nombre_servicio, Descripcion_servicio, Metodo_pago, Activado)
         VALUES (:codigo, :nombre, :descripcion, :metodo, 1)"
      );
      $stmt->execute([
        ':codigo'      => $codigo,
        ':nombre'      => $nombre,
        ':descripcion' => $descripcion,
        ':metodo'      => $metodo,
      ]);
    } catch (\PDOException $e) {
      if ($e->getCode() === '23000') {   // clave duplicada
        throw new \DomainException("Ya existe un servicio con el código {$codigo}.");
      }
      throw $e;
    }
  }

  /** Crea la fila si no existe, o la actualiza si existe (así también "cura" servicios sin sincronizar). */
  public static function guardar(string $codigo, string $nombre, string $descripcion, string $metodo, int $activado): void {
    $pdo = DB::getInstance();

    $stmt = $pdo->prepare("SELECT 1 FROM Servicios WHERE Id_servicio = :codigo");
    $stmt->execute([':codigo' => $codigo]);

    if ($stmt->fetchColumn()) {
      $up = $pdo->prepare(
        "UPDATE Servicios
         SET Nombre_servicio = :nombre, Descripcion_servicio = :descripcion, Metodo_pago = :metodo, Activado = :activado
         WHERE Id_servicio = :codigo"
      );
      $up->execute([
        ':nombre' => $nombre, ':descripcion' => $descripcion, ':metodo' => $metodo,
        ':activado' => $activado, ':codigo' => $codigo,
      ]);
      return;
    }

    $ins = $pdo->prepare(
      "INSERT INTO Servicios (Id_servicio, Nombre_servicio, Descripcion_servicio, Metodo_pago, Activado)
       VALUES (:codigo, :nombre, :descripcion, :metodo, :activado)"
    );
    $ins->execute([
      ':codigo' => $codigo, ':nombre' => $nombre, ':descripcion' => $descripcion,
      ':metodo' => $metodo, ':activado' => $activado,
    ]);
  }

  /** tipo de precio del panel => valor del ENUM Metodo_pago */
  public static function metodoDesdeTipo(string $tipo): string {
    $mapa = ['unico' => 'Unico', 'semanal' => 'Semanal', 'mensual' => 'Mensual', 'anual' => 'Anual'];
    return $mapa[$tipo] ?? 'Mensual';
  }
}
