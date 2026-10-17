<?php

namespace App\modelo;

use App\core\DB;

/**
 * Tabla Chats: una fila por conversación entre un usuario y un administrador.
 * Estados: 'esperando' (nadie lo ha cogido) → 'activo' (lo atiende un admin) → 'cerrado'.
 */
class chatModelo {

  public static function rolDeUsuario(string $correo): ?string {
    $stmt = DB::getInstance()->prepare("SELECT Rol FROM Usuarios WHERE Correo = :email");
    $stmt->execute([':email' => $correo]);
    $rol = $stmt->fetchColumn();
    return $rol === false ? null : $rol;
  }

  public static function crear(string $correoUsuario): int {
    $pdo = DB::getInstance();
    $stmt = $pdo->prepare("INSERT INTO Chats (Correo_usuario, Estado) VALUES (:email, 'esperando')");
    $stmt->execute([':email' => $correoUsuario]);
    return (int) $pdo->lastInsertId();
  }

  // Evita duplicados si el usuario recarga la página o vuelve a abrir la burbuja
  public static function abiertoDeUsuario(string $correo) {
    $stmt = DB::getInstance()->prepare(
      "SELECT Id_chat, Estado FROM Chats
       WHERE Correo_usuario = :email AND Estado IN ('esperando','activo')
       ORDER BY Id_chat DESC LIMIT 1"
    );
    $stmt->execute([':email' => $correo]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  public static function porId(int $id) {
    $stmt = DB::getInstance()->prepare(
      "SELECT c.Id_chat, c.Correo_usuario, c.Correo_admin, c.Estado, u.Nombre, u.Apellido
       FROM Chats c JOIN Usuarios u ON u.Correo = c.Correo_usuario
       WHERE c.Id_chat = :id"
    );
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  public static function pendientes(): array {
    $stmt = DB::getInstance()->query(
      "SELECT c.Id_chat, c.Correo_usuario, DATE_FORMAT(c.Fecha_creacion, '%H:%i') AS Hora, u.Nombre, u.Apellido
       FROM Chats c JOIN Usuarios u ON u.Correo = c.Correo_usuario
       WHERE c.Estado = 'esperando'
       ORDER BY c.Fecha_creacion ASC"
    );
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  // Conversaciones que este admin ya cogió y siguen abiertas (para retomarlas tras recargar)
  public static function activosDeAdmin(string $correoAdmin): array {
    $stmt = DB::getInstance()->prepare(
      "SELECT c.Id_chat, c.Correo_usuario, DATE_FORMAT(c.Fecha_asignacion, '%H:%i') AS Hora, u.Nombre, u.Apellido
       FROM Chats c JOIN Usuarios u ON u.Correo = c.Correo_usuario
       WHERE c.Estado = 'activo' AND c.Correo_admin = :admin
       ORDER BY c.Fecha_asignacion DESC"
    );
    $stmt->execute([':admin' => $correoAdmin]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  // true si logró asignárselo (el WHERE evita que dos admins cojan el mismo chat a la vez)
  public static function asignar(int $id, string $correoAdmin): bool {
    $stmt = DB::getInstance()->prepare(
      "UPDATE Chats SET Correo_admin = :admin, Estado = 'activo', Fecha_asignacion = NOW()
       WHERE Id_chat = :id AND Estado = 'esperando'"
    );
    $stmt->execute([':admin' => $correoAdmin, ':id' => $id]);
    return $stmt->rowCount() > 0;
  }

  public static function cerrar(int $id): void {
    $stmt = DB::getInstance()->prepare(
      "UPDATE Chats SET Estado = 'cerrado', Fecha_cierre = NOW() WHERE Id_chat = :id AND Estado <> 'cerrado'"
    );
    $stmt->execute([':id' => $id]);
  }
}
