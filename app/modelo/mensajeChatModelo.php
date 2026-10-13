<?php

namespace App\modelo;

use App\core\DB;

/**
 * Tabla Mensajes_chat. Todos los métodos devuelven los mensajes con la MISMA
 * forma que viaja por Pusher y por las respuestas HTTP, para que el JS pinte
 * siempre igual:  { id, correo, nombre, rol, mensaje, fecha }
 */
class mensajeChatModelo {

  public static function crear(int $idChat, string $correoEmisor, string $rolEmisor, string $mensaje): array {
    $pdo = DB::getInstance();
    $stmt = $pdo->prepare(
      "INSERT INTO Mensajes_chat (Id_chat, Correo_emisor, Rol_emisor, Mensaje)
       VALUES (:chat, :correo, :rol, :mensaje)"
    );
    $stmt->execute([
      ':chat'    => $idChat,
      ':correo'  => $correoEmisor,
      ':rol'     => $rolEmisor,
      ':mensaje' => $mensaje,
    ]);

    return self::porId((int) $pdo->lastInsertId());
  }

  public static function porId(int $id): array {
    $stmt = DB::getInstance()->prepare(self::SELECT . " WHERE m.Id_mensaje = :id");
    $stmt->execute([':id' => $id]);
    return self::formatear($stmt->fetch(\PDO::FETCH_ASSOC));
  }

  public static function historial(int $idChat): array {
    $stmt = DB::getInstance()->prepare(self::SELECT . " WHERE m.Id_chat = :chat ORDER BY m.Id_mensaje ASC");
    $stmt->execute([':chat' => $idChat]);
    return array_map([self::class, 'formatear'], $stmt->fetchAll(\PDO::FETCH_ASSOC));
  }

  private const SELECT =
    "SELECT m.Id_mensaje, m.Correo_emisor, m.Rol_emisor, m.Mensaje,
            DATE_FORMAT(m.Fecha_envio, '%H:%i') AS Hora, u.Nombre
     FROM Mensajes_chat m
     LEFT JOIN Usuarios u ON u.Correo = m.Correo_emisor";

  private static function formatear(array $fila): array {
    return [
      'id'      => (int) $fila['Id_mensaje'],
      'correo'  => $fila['Correo_emisor'],
      'nombre'  => $fila['Nombre'] ?? '',
      'rol'     => $fila['Rol_emisor'],
      'mensaje' => $fila['Mensaje'],
      'fecha'   => $fila['Hora'],
    ];
  }
}
