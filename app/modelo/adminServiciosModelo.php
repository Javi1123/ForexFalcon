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

  /** Tipos de servicio (columna Servicios.Tipo_servicio): qué contenido ofrece. */
  public const TIPOS = ['video', 'imagenes', 'chat'];

  /** código (en mayúsculas) => Activado (0/1) */
  public static function existentes(): array {
    $filas = DB::getInstance()->query("SELECT Id_servicio, Activado FROM Servicios")->fetchAll(\PDO::FETCH_ASSOC);
    $mapa = [];
    foreach ($filas as $f) {
      $mapa[strtoupper($f['Id_servicio'])] = (int) $f['Activado'];
    }
    return $mapa;
  }

  /** código (en mayúsculas) => tipo de servicio ('video' | 'imagenes' | 'chat') */
  public static function tipos(): array {
    $filas = DB::getInstance()->query("SELECT Id_servicio, Tipo_servicio FROM Servicios")->fetchAll(\PDO::FETCH_ASSOC);
    $mapa = [];
    foreach ($filas as $f) {
      $mapa[strtoupper($f['Id_servicio'])] = $f['Tipo_servicio'];
    }
    return $mapa;
  }

  /** Un servicio por su código (Id_servicio, Nombre_servicio, Descripcion_servicio, Tipo_servicio), o false. */
  public static function porCodigo(string $codigo) {
    $stmt = DB::getInstance()->prepare(
      "SELECT Id_servicio, Nombre_servicio, Descripcion_servicio, Tipo_servicio FROM Servicios WHERE Id_servicio = :codigo"
    );
    $stmt->execute([':codigo' => $codigo]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  /** Alta de un servicio nuevo. Lanza \DomainException si el código ya existe. */
  public static function insertar(string $codigo, string $nombre, string $descripcion, string $metodo, string $tipo = 'video'): void {
    $pdo = DB::getInstance();
    try {
      $stmt = $pdo->prepare(
        "INSERT INTO Servicios (Id_servicio, Nombre_servicio, Descripcion_servicio, Metodo_pago, Tipo_servicio, Activado)
         VALUES (:codigo, :nombre, :descripcion, :metodo, :tipo, 1)"
      );
      $stmt->execute([
        ':codigo'      => $codigo,
        ':nombre'      => $nombre,
        ':descripcion' => $descripcion,
        ':metodo'      => $metodo,
        ':tipo'        => $tipo,
      ]);
    } catch (\PDOException $e) {
      if ($e->getCode() === '23000') {   // clave duplicada
        throw new \DomainException("Ya existe un servicio con el código {$codigo}.");
      }
      throw $e;
    }
  }

  /**
   * Crea la fila si no existe, o la actualiza si existe (así también "cura" servicios sin sincronizar).
   * $tipo = null  →  NO se toca el tipo de servicio que ya tuviera (archivar, cambiar precio, sincronizar...).
   *                  Si la fila es nueva, empieza como 'video'.
   */
  public static function guardar(string $codigo, string $nombre, string $descripcion, string $metodo, int $activado, ?string $tipo = null): void {
    $pdo = DB::getInstance();

    $stmt = $pdo->prepare("SELECT 1 FROM Servicios WHERE Id_servicio = :codigo");
    $stmt->execute([':codigo' => $codigo]);

    if ($stmt->fetchColumn()) {
      $campos = "Nombre_servicio = :nombre, Descripcion_servicio = :descripcion, Metodo_pago = :metodo, Activado = :activado";
      $params = [
        ':nombre' => $nombre, ':descripcion' => $descripcion, ':metodo' => $metodo,
        ':activado' => $activado, ':codigo' => $codigo,
      ];
      if ($tipo !== null) {
        $campos .= ", Tipo_servicio = :tipo";
        $params[':tipo'] = $tipo;
      }
      $up = $pdo->prepare("UPDATE Servicios SET {$campos} WHERE Id_servicio = :codigo");
      $up->execute($params);
      return;
    }

    $ins = $pdo->prepare(
      "INSERT INTO Servicios (Id_servicio, Nombre_servicio, Descripcion_servicio, Metodo_pago, Tipo_servicio, Activado)
       VALUES (:codigo, :nombre, :descripcion, :metodo, :tipo, :activado)"
    );
    $ins->execute([
      ':codigo' => $codigo, ':nombre' => $nombre, ':descripcion' => $descripcion,
      ':metodo' => $metodo, ':tipo' => $tipo ?? 'video', ':activado' => $activado,
    ]);
  }

  /** tipo de precio del panel => valor del ENUM Metodo_pago */
  public static function metodoDesdeTipo(string $tipo): string {
    $mapa = ['unico' => 'Unico', 'semanal' => 'Semanal', 'mensual' => 'Mensual', 'anual' => 'Anual'];
    return $mapa[$tipo] ?? 'Mensual';
  }
}
