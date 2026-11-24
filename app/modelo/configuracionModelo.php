<?php

namespace App\modelo;

use \App\core\DB;
use \App\core\Contrasena;

/**
 * Datos de la ventana "Configuración" (la misma para usuarios y administradores).
 * Todo se hace sobre el correo de la SESIÓN: nunca se acepta un correo enviado por el navegador.
 * Las fechas las pone PHP (no NOW() de MySQL) para que caducidades y límites usen siempre el mismo reloj.
 */
class configuracionModelo {

  public const MINUTOS_CODIGO   = 10;   // lo que dura un código
  private const ESPERA_SEGUNDOS = 60;   // mínimo entre dos envíos
  private const MAX_ENVIOS_HORA = 5;    // máximo de envíos por hora
  private const MAX_INTENTOS    = 5;    // fallos antes de invalidar el código

  /** Datos que se muestran en el formulario (nunca la contraseña). */
  public static function perfil(string $correo): ?array {
    $st = DB::getInstance()->prepare("SELECT Correo, Nombre, Apellido, Telefono, Pais, Rol FROM Usuarios WHERE Correo = :c");
    $st->execute([':c' => $correo]);
    $f = $st->fetch(\PDO::FETCH_ASSOC);
    return $f ?: null;
  }

  public static function hashContrasena(string $correo): ?string {
    $st = DB::getInstance()->prepare("SELECT contraseña FROM Usuarios WHERE Correo = :c");
    $st->execute([':c' => $correo]);
    $h = $st->fetchColumn();
    return $h === false ? null : (string) $h;
  }

  /** Guarda la contraseña nueva y deja sin efecto los enlaces de "he olvidado mi contraseña" que hubiera pendientes. */
  public static function cambiarContrasena(string $correo, string $nueva): void {
    $pdo = DB::getInstance();
    $pdo->prepare("UPDATE Usuarios SET contraseña = :p WHERE Correo = :c")
        ->execute([':p' => Contrasena::hashear($nueva), ':c' => $correo]);

    try {
      $pdo->prepare("UPDATE Restablecer_contrasena SET Usado = 1 WHERE Correo = :c AND Usado = 0")->execute([':c' => $correo]);
    } catch (\Throwable $e) {
      // La tabla de restablecimiento puede no existir todavía: no debe impedir el cambio de contraseña
    }
  }

  // ───────────────────────── Verificación del correo ─────────────────────────
  //
  // Código de 6 dígitos hasheado, con caducidad, límite de intentos y de envíos.

  private const T_CORREO   = 'Verificacion_correo';

  public static function correoVerificado(string $correo): bool {
    try {
      $st = DB::getInstance()->prepare("SELECT Verificado_en FROM " . self::T_CORREO . " WHERE Correo = :c");
      $st->execute([':c' => $correo]);
      return !empty($st->fetchColumn());
    } catch (\Throwable $e) {
      error_log('Verificacion_correo no disponible: ' . $e->getMessage());
      return false;
    }
  }

  /**
   * Crea un código de 6 dígitos para el correo y lo guarda (hasheado). Devuelve:
   *   ['codigo' => '123456']                  → hay que enviarlo
   *   ['error' => 'espera', 'segundos' => N]  → se pidió hace menos de un minuto
   *   ['error' => 'limite']                   → demasiados envíos en la última hora
   */
  public static function crearCodigo(string $correo): array {
    return self::nuevoCodigo(self::T_CORREO, $correo, null);
  }

  /** Comprueba el código. Devuelve 'ok' | 'incorrecto' | 'caducado' | 'bloqueado' | 'sin_codigo'. */
  public static function comprobarCodigo(string $correo, string $codigo): string {
    return self::verificarCodigo(self::T_CORREO, $correo, $codigo, null);
  }

  private static function nuevoCodigo(string $tabla, string $correo, ?string $numero): array {
    $pdo   = DB::getInstance();
    $ahora = time();

    $st = $pdo->prepare("SELECT Fecha_envio, Envios_hora, Inicio_ventana FROM $tabla WHERE Correo = :c");
    $st->execute([':c' => $correo]);
    $f = $st->fetch(\PDO::FETCH_ASSOC);

    $envios = 0;
    $inicio = $ahora;
    if ($f) {
      if (!empty($f['Fecha_envio']) && $ahora - strtotime($f['Fecha_envio']) < self::ESPERA_SEGUNDOS) {
        return ['error' => 'espera', 'segundos' => self::ESPERA_SEGUNDOS - ($ahora - strtotime($f['Fecha_envio']))];
      }
      if (!empty($f['Inicio_ventana']) && $ahora - strtotime($f['Inicio_ventana']) < 3600) {
        $envios = (int) $f['Envios_hora'];
        $inicio = strtotime($f['Inicio_ventana']);
        if ($envios >= self::MAX_ENVIOS_HORA) {
          return ['error' => 'limite'];
        }
      }
    }

    $codigo = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $datos  = [
      ':h'  => password_hash($codigo, PASSWORD_BCRYPT, ['cost' => 10]),
      ':ex' => date('Y-m-d H:i:s', $ahora + self::MINUTOS_CODIGO * 60),
      ':en' => date('Y-m-d H:i:s', $ahora),
      ':n'  => $envios + 1,
      ':iv' => date('Y-m-d H:i:s', $inicio),
      ':c'  => $correo,
    ];
    $colNum = $numero !== null;   // solo la tabla del teléfono guarda el número pendiente
    if ($colNum) {
      $datos[':num'] = $numero;
    }

    if ($f) {
      $pdo->prepare("UPDATE $tabla SET Codigo_hash = :h, Fecha_expira = :ex, Intentos = 0, Fecha_envio = :en, Envios_hora = :n, Inicio_ventana = :iv"
          . ($colNum ? ", Numero_pendiente = :num" : "") . " WHERE Correo = :c")->execute($datos);
    } else {
      $pdo->prepare("INSERT INTO $tabla (Correo, Codigo_hash, Fecha_expira, Intentos, Fecha_envio, Envios_hora, Inicio_ventana" . ($colNum ? ", Numero_pendiente" : "")
          . ") VALUES (:c, :h, :ex, 0, :en, :n, :iv" . ($colNum ? ", :num" : "") . ")")->execute($datos);
    }

    return ['codigo' => $codigo];
  }

  private static function verificarCodigo(string $tabla, string $correo, string $codigo, ?string $numero): string {
    $pdo = DB::getInstance();
    $esTel = $numero !== null;
    $st  = $pdo->prepare("SELECT Codigo_hash, Fecha_expira, Intentos" . ($esTel ? ", Numero_pendiente" : "") . " FROM $tabla WHERE Correo = :c");
    $st->execute([':c' => $correo]);
    $f = $st->fetch(\PDO::FETCH_ASSOC);

    if (!$f || empty($f['Codigo_hash'])) {
      return 'sin_codigo';
    }
    // El código se envió a un número concreto: si el teléfono guardado cambió desde entonces, ese código ya no vale
    if ($esTel && ($f['Numero_pendiente'] ?? null) !== $numero) {
      return 'sin_codigo';
    }
    if (strtotime($f['Fecha_expira']) < time()) {
      return 'caducado';
    }
    if ((int) $f['Intentos'] >= self::MAX_INTENTOS) {
      return 'bloqueado';
    }

    if (!password_verify($codigo, $f['Codigo_hash'])) {
      $pdo->prepare("UPDATE $tabla SET Intentos = Intentos + 1 WHERE Correo = :c")->execute([':c' => $correo]);
      return ((int) $f['Intentos'] + 1) >= self::MAX_INTENTOS ? 'bloqueado' : 'incorrecto';
    }

    // Correcto: se gasta el código y se marca como verificado
    $ahora = date('Y-m-d H:i:s');
    if ($esTel) {
      $pdo->prepare("UPDATE $tabla SET Codigo_hash = NULL, Fecha_expira = NULL, Intentos = 0, Numero_pendiente = NULL, Numero_verificado = :num, Verificado_en = :v WHERE Correo = :c")
          ->execute([':num' => $numero, ':v' => $ahora, ':c' => $correo]);
    } else {
      $pdo->prepare("UPDATE $tabla SET Codigo_hash = NULL, Fecha_expira = NULL, Intentos = 0, Verificado_en = :v WHERE Correo = :c")
          ->execute([':v' => $ahora, ':c' => $correo]);
    }
    return 'ok';
  }
}
