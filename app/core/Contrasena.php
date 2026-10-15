<?php

namespace App\core;

/**
 * Contraseñas: hash seguro (bcrypt) + compatibilidad con las antiguas (SHA-256 sin sal).
 *
 * Por qué bcrypt y no SHA-256: SHA-256 está hecho para ser rápido (miles de millones de intentos por
 * segundo con una tarjeta gráfica), justo lo contrario de lo que se quiere al guardar contraseñas.
 * bcrypt es lento a propósito (~250 ms con coste 12) y añade una sal distinta a cada contraseña, así que
 * dos usuarios con la misma contraseña tienen hashes distintos.
 *
 * Migración sin que nadie se entere: las cuentas existentes siguen con su hash SHA-256 hasta que su dueño
 * inicia sesión. En ese momento, como ya conocemos la contraseña en claro, se comprueba con el método viejo
 * y se guarda de nuevo con bcrypt. No hace falta resetear contraseñas ni avisar a nadie.
 *
 * Se usa PASSWORD_BCRYPT (y no PASSWORD_DEFAULT) a propósito: da siempre 60 caracteres, así que sigue
 * cabiendo en la columna actual contraseña VARCHAR(64). Si algún día cambias a Argon2 o a PASSWORD_DEFAULT,
 * amplía antes la columna a VARCHAR(255).
 *
 * Límite conocido de bcrypt: solo lee los primeros 72 bytes de la contraseña.
 */
class Contrasena {

  /** Subir este número en el futuro re-guarda los hashes automáticamente en el siguiente inicio de sesión. */
  private const COSTE = 12;

  /**
   * Hash de una contraseña inventada, solo para igualar tiempos (ver simularVerificacion()).
   * No corresponde a la contraseña de ningún usuario.
   */
  private const HASH_DE_RELLENO = '$2y$12$uTqJFob7F73z5xGmxVqiUuA7XtfZUYvbX37ph5lwoKPd4V3c3ivgm';

  /** Hash bcrypt listo para guardar en la base de datos (60 caracteres). */
  public static function hashear(string $contrasena): string {
    return password_hash($contrasena, PASSWORD_BCRYPT, ['cost' => self::COSTE]);
  }

  /** ¿Es un hash de los antiguos? (SHA-256 en hexadecimal: 64 caracteres 0-9 a-f) */
  public static function esAntiguo(string $almacenado): bool {
    return (bool) preg_match('/^[a-f0-9]{64}$/i', $almacenado);
  }

  /** ¿Coincide la contraseña con lo guardado? Sirve tanto para hashes nuevos como antiguos. */
  public static function verificar(string $contrasena, string $almacenado): bool {
    if (self::esAntiguo($almacenado)) {
      // hash_equals compara en tiempo constante (el === puede filtrar información por el tiempo que tarda)
      return hash_equals(strtolower($almacenado), hash('sha256', $contrasena));
    }
    return password_verify($contrasena, $almacenado);
  }

  /** ¿Hay que volver a guardarla? Sí si es antigua o si se ha subido el coste desde que se guardó. */
  public static function necesitaActualizar(string $almacenado): bool {
    return self::esAntiguo($almacenado)
        || password_needs_rehash($almacenado, PASSWORD_BCRYPT, ['cost' => self::COSTE]);
  }

  /**
   * Para cuando el correo NO existe: gasta el mismo tiempo que una comprobación real.
   * Sin esto, con bcrypt (lento) un correo registrado tardaría ~250 ms más en dar error que uno que no
   * existe, y cualquiera podría averiguar qué correos tienen cuenta midiendo la respuesta.
   */
  public static function simularVerificacion(string $contrasena): void {
    password_verify($contrasena, self::HASH_DE_RELLENO);
  }
}
