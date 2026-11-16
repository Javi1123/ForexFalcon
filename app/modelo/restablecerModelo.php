<?php

namespace App\modelo;

use \App\core\DB;
use \App\core\Contrasena;

/**
 * Enlaces para restablecer la contraseña.
 * El token (64 caracteres hexadecimales, 256 bits aleatorios) solo viaja en el correo. En la base de datos solo se
 * guarda su SHA-256, así que un volcado de la tabla no sirve para restablecer ninguna cuenta.
 * Las fechas las pone PHP (no NOW() de MySQL) para que caducidad y límites usen siempre el mismo reloj.
 */
class restablecerModelo {

  public const MINUTOS_VALIDEZ  = 60;   // lo que dura un enlace
  private const ESPERA_SEGUNDOS = 60;   // mínimo entre dos solicitudes del mismo correo
  private const MAX_POR_HORA    = 5;    // máximo de enlaces por correo y hora

  /**
   * Crea un enlace nuevo para ese correo (que debe existir en Usuarios) y devuelve el token en claro,
   * o null si se ha pedido demasiadas veces (límite anti-abuso: evita inundar de correos a una persona).
   * Al crear uno nuevo, los anteriores dejan de valer.
   */
  public static function crear(string $correo, ?string $ip = null): ?string {
    $pdo   = DB::getInstance();
    $ahora = time();

    $st = $pdo->prepare("SELECT COUNT(*) AS n, MAX(Fecha_creacion) AS ultima FROM Restablecer_contrasena WHERE Correo = :c AND Fecha_creacion >= :desde");
    $st->execute([':c' => $correo, ':desde' => date('Y-m-d H:i:s', $ahora - 3600)]);
    $r = $st->fetch(\PDO::FETCH_ASSOC);

    if ((int) $r['n'] >= self::MAX_POR_HORA) {
      return null;
    }
    if (!empty($r['ultima']) && $ahora - strtotime($r['ultima']) < self::ESPERA_SEGUNDOS) {
      return null;
    }

    // Limpieza: enlaces de hace más de un día ya no sirven para nada
    $pdo->prepare("DELETE FROM Restablecer_contrasena WHERE Fecha_expira < :limite")
        ->execute([':limite' => date('Y-m-d H:i:s', $ahora - 86400)]);

    // Los enlaces anteriores sin usar quedan invalidados
    $pdo->prepare("UPDATE Restablecer_contrasena SET Usado = 1 WHERE Correo = :c AND Usado = 0")
        ->execute([':c' => $correo]);

    $token = bin2hex(random_bytes(32));
    $pdo->prepare("INSERT INTO Restablecer_contrasena (Correo, Token_hash, Fecha_creacion, Fecha_expira, Ip_address) VALUES (:c, :h, :creado, :expira, :ip)")
        ->execute([
          ':c'      => $correo,
          ':h'      => hash('sha256', $token),
          ':creado' => date('Y-m-d H:i:s', $ahora),
          ':expira' => date('Y-m-d H:i:s', $ahora + self::MINUTOS_VALIDEZ * 60),
          ':ip'     => $ip !== null ? substr($ip, 0, 45) : null,
        ]);

    return $token;
  }

  /** Correo al que pertenece el token si es válido (existe, sin usar y sin caducar); si no, null. */
  public static function correoDeToken(string $token): ?string {
    if (!preg_match('/^[a-f0-9]{64}$/', $token)) {
      return null;
    }
    $st = DB::getInstance()->prepare("SELECT Correo FROM Restablecer_contrasena WHERE Token_hash = :h AND Usado = 0 AND Fecha_expira > :ahora");
    $st->execute([':h' => hash('sha256', $token), ':ahora' => date('Y-m-d H:i:s')]);
    $c = $st->fetchColumn();
    return $c === false ? null : (string) $c;
  }

  /**
   * Cambia la contraseña y gasta el enlace (y cualquier otro pendiente de esa cuenta) en una sola transacción.
   * El UPDATE ... Usado = 0 hace que, si dos peticiones llegan a la vez con el mismo enlace, solo una gane.
   */
  public static function aplicar(string $token, string $contrasenaNueva): bool {
    $pdo = DB::getInstance();
    $pdo->beginTransaction();
    try {
      $gasto = $pdo->prepare("UPDATE Restablecer_contrasena SET Usado = 1 WHERE Token_hash = :h AND Usado = 0 AND Fecha_expira > :ahora");
      $gasto->execute([':h' => hash('sha256', $token), ':ahora' => date('Y-m-d H:i:s')]);
      if ($gasto->rowCount() !== 1) {
        $pdo->rollBack();
        return false;
      }

      $st = $pdo->prepare("SELECT Correo FROM Restablecer_contrasena WHERE Token_hash = :h");
      $st->execute([':h' => hash('sha256', $token)]);
      $correo = (string) $st->fetchColumn();

      $pdo->prepare("UPDATE Usuarios SET contraseña = :p WHERE Correo = :c")
          ->execute([':p' => Contrasena::hashear($contrasenaNueva), ':c' => $correo]);

      $pdo->prepare("UPDATE Restablecer_contrasena SET Usado = 1 WHERE Correo = :c")
          ->execute([':c' => $correo]);

      $pdo->commit();
      return true;
    } catch (\Throwable $e) {
      if ($pdo->inTransaction()) {
        $pdo->rollBack();
      }
      throw $e;
    }
  }
}
