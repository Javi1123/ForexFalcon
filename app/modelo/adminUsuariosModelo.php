<?php

namespace App\modelo;

use App\core\DB;

/**
 * Consultas de la administración de usuarios y de actividad (tablas Usuarios, Registro_actividad,
 * Suscripciones y Servicios). Nunca se devuelve la contraseña (columna "contraseña").
 *
 * Todas las consultas usan parámetros preparados; los LIKE escapan % y _ del texto buscado.
 */
class adminUsuariosModelo {

  public const ROLES = ['Usuario', 'Administrador', 'Superusuario'];

  /** Columnas públicas de un usuario (sin contraseña). */
  private const COLUMNAS = "u.Correo, u.Nombre, u.Apellido, u.Telefono, u.Pais, u.Rol, u.Fecha_nacimiento,
                            u.Fecha_creacion, u.Ultimo_login, u.Ultimo_logout, u.En_linea";

  // ───────────────────────── Resumen (panel) ─────────────────────────

  public static function resumen(): array {
    $pdo = DB::getInstance();
    $hace7 = date('Y-m-d H:i:s', strtotime('-7 days'));
    $hoy = date('Y-m-d 00:00:00');

    $uno = function (string $sql, array $p = []) use ($pdo) {
      $st = $pdo->prepare($sql);
      $st->execute($p);
      return (int) $st->fetchColumn();
    };

    return [
      'usuarios'        => $uno("SELECT COUNT(*) FROM Usuarios"),
      'nuevos7'         => $uno("SELECT COUNT(*) FROM Usuarios WHERE Fecha_creacion >= :d", [':d' => $hace7]),
      'en_linea'        => $uno("SELECT COUNT(*) FROM Usuarios WHERE En_linea = 1"),
      'administradores' => $uno("SELECT COUNT(*) FROM Usuarios WHERE Rol IN ('Administrador','Superusuario')"),
      'servicios'       => $uno("SELECT COUNT(*) FROM Servicios WHERE Activado = 1"),
      'suscripciones'   => $uno("SELECT COUNT(*) FROM Suscripciones WHERE Activado = 1 AND (Fecha_fin IS NULL OR Fecha_fin > :ahora)", [':ahora' => date('Y-m-d H:i:s')]),
      'logins_hoy'      => $uno("SELECT COUNT(*) FROM Registro_actividad WHERE Tipo_evento = 'login' AND Fecha_evento >= :d", [':d' => $hoy]),
    ];
  }

  /** Últimos usuarios registrados. */
  public static function ultimosRegistrados(int $n = 5): array {
    $st = DB::getInstance()->prepare("SELECT " . self::COLUMNAS . " FROM Usuarios u ORDER BY u.Fecha_creacion DESC LIMIT " . (int) $n);
    $st->execute();
    return $st->fetchAll(\PDO::FETCH_ASSOC);
  }

  // ───────────────────────── Usuarios ─────────────────────────

  /** Convierte lo que escribe el administrador en un patrón LIKE seguro. */
  private static function like(string $texto): string {
    return '%' . strtr($texto, ['!' => '!!', '%' => '!%', '_' => '!_']) . '%';
  }

  /**
   * Lista paginada. $f = ['q' => texto, 'rol' => '', 'estado' => '' | 'en_linea' | 'desconectado', 'orden' => ...]
   * Devuelve ['filas' => [...], 'total' => n].
   */
  public static function listar(array $f, int $pagina, int $porPagina): array {
    $pdo = DB::getInstance();
    $donde = [];
    $p = [];

    $q = trim((string) ($f['q'] ?? ''));
    if ($q !== '') {
      $donde[] = "(u.Correo LIKE :q ESCAPE '!' OR u.Nombre LIKE :q ESCAPE '!' OR u.Apellido LIKE :q ESCAPE '!'
                   OR CONCAT(u.Nombre, ' ', u.Apellido) LIKE :q ESCAPE '!' OR u.Telefono LIKE :q ESCAPE '!' OR u.Pais LIKE :q ESCAPE '!')";
      $p[':q'] = self::like($q);
    }
    if (in_array($f['rol'] ?? '', self::ROLES, true)) {
      $donde[] = "u.Rol = :rol";
      $p[':rol'] = $f['rol'];
    }
    if (($f['estado'] ?? '') === 'en_linea')     { $donde[] = "u.En_linea = 1"; }
    if (($f['estado'] ?? '') === 'desconectado') { $donde[] = "u.En_linea = 0"; }

    $where = $donde ? 'WHERE ' . implode(' AND ', $donde) : '';

    $ordenes = [
      'recientes' => 'u.Fecha_creacion DESC',
      'antiguos'  => 'u.Fecha_creacion ASC',
      'nombre'    => 'u.Nombre ASC, u.Apellido ASC',
      'acceso'    => 'CASE WHEN u.Ultimo_login IS NULL THEN 1 ELSE 0 END, u.Ultimo_login DESC',
    ];
    $orden = $ordenes[$f['orden'] ?? ''] ?? $ordenes['recientes'];

    $st = $pdo->prepare("SELECT COUNT(*) FROM Usuarios u $where");
    $st->execute($p);
    $total = (int) $st->fetchColumn();

    $pagina = max(1, $pagina);
    $st = $pdo->prepare(
      "SELECT " . self::COLUMNAS . ",
              (SELECT COUNT(*) FROM Suscripciones s WHERE s.Correo = u.Correo AND s.Activado = 1
                 AND (s.Fecha_fin IS NULL OR s.Fecha_fin > :ahora)) AS Servicios_vigentes
       FROM Usuarios u $where
       ORDER BY $orden
       LIMIT " . (int) $porPagina . " OFFSET " . (int) (($pagina - 1) * $porPagina)
    );
    $st->execute($p + [':ahora' => date('Y-m-d H:i:s')]);

    return ['filas' => $st->fetchAll(\PDO::FETCH_ASSOC), 'total' => $total];
  }

  public static function porCorreo(string $correo) {
    $st = DB::getInstance()->prepare("SELECT " . self::COLUMNAS . " FROM Usuarios u WHERE u.Correo = :c");
    $st->execute([':c' => $correo]);
    return $st->fetch(\PDO::FETCH_ASSOC);
  }

  /** Ficha completa: datos + servicios contratados + últimos eventos de acceso. */
  public static function ficha(string $correo): ?array {
    $u = self::porCorreo($correo);
    if (!$u) {
      return null;
    }
    $pdo = DB::getInstance();

    $st = $pdo->prepare(
      "SELECT sv.Id_servicio, sv.Nombre_servicio, sv.Tipo_servicio, s.Fecha_inicio, s.Fecha_fin, s.Activado
       FROM Suscripciones s JOIN Servicios sv ON sv.Id_servicio = s.Id_servicio
       WHERE s.Correo = :c ORDER BY s.Fecha_inicio DESC LIMIT 20"
    );
    $st->execute([':c' => $correo]);
    $subs = $st->fetchAll(\PDO::FETCH_ASSOC);
    $ahora = date('Y-m-d H:i:s');
    foreach ($subs as &$s) {
      $s['Vigente'] = ((int) $s['Activado'] === 1 && ($s['Fecha_fin'] === null || $s['Fecha_fin'] > $ahora)) ? 1 : 0;
    }
    unset($s);

    $st = $pdo->prepare(
      "SELECT Tipo_evento, Fecha_evento, Ip_address, User_agent FROM Registro_actividad
       WHERE Correo = :c ORDER BY Fecha_evento DESC, Id_registro DESC LIMIT 8"
    );
    $st->execute([':c' => $correo]);
    $eventos = $st->fetchAll(\PDO::FETCH_ASSOC);
    foreach ($eventos as &$e) {
      $e['Dispositivo'] = self::dispositivo((string) $e['User_agent']);
      unset($e['User_agent']);
    }
    unset($e);

    return ['usuario' => $u, 'suscripciones' => $subs, 'eventos' => $eventos];
  }

  public static function actualizarDatos(string $correo, string $nombre, string $apellido, string $telefono, string $pais): void {
    $st = DB::getInstance()->prepare(
      "UPDATE Usuarios SET Nombre = :n, Apellido = :a, Telefono = :t, Pais = :p WHERE Correo = :c"
    );
    $st->execute([':n' => $nombre, ':a' => $apellido, ':t' => $telefono, ':p' => $pais, ':c' => $correo]);
  }

  public static function cambiarRol(string $correo, string $rol): void {
    $st = DB::getInstance()->prepare("UPDATE Usuarios SET Rol = :r WHERE Correo = :c");
    $st->execute([':r' => $rol, ':c' => $correo]);
  }

  /** Cuántas suscripciones (de cualquier estado) tiene: la clave foránea impide borrar si hay alguna. */
  public static function contarSuscripciones(string $correo): int {
    $st = DB::getInstance()->prepare("SELECT COUNT(*) FROM Suscripciones WHERE Correo = :c");
    $st->execute([':c' => $correo]);
    return (int) $st->fetchColumn();
  }

  public static function eliminar(string $correo): void {
    $st = DB::getInstance()->prepare("DELETE FROM Usuarios WHERE Correo = :c");
    $st->execute([':c' => $correo]);
  }

  // ───────────────────────── Actividad ─────────────────────────

  /** Condiciones comunes de la actividad: ['q' => correo/nombre, 'tipo' => login|logout, 'desde' => Y-m-d, 'hasta' => Y-m-d]. */
  private static function condicionesActividad(array $f): array {
    $donde = [];
    $p = [];
    $q = trim((string) ($f['q'] ?? ''));
    if ($q !== '') {
      $donde[] = "(r.Correo LIKE :q ESCAPE '!' OR u.Nombre LIKE :q ESCAPE '!' OR u.Apellido LIKE :q ESCAPE '!'
                   OR CONCAT(u.Nombre, ' ', u.Apellido) LIKE :q ESCAPE '!' OR r.Ip_address LIKE :q ESCAPE '!')";
      $p[':q'] = self::like($q);
    }
    if (in_array($f['tipo'] ?? '', ['login', 'logout'], true)) {
      $donde[] = "r.Tipo_evento = :tipo";
      $p[':tipo'] = $f['tipo'];
    }
    if (!empty($f['desde'])) {
      $donde[] = "r.Fecha_evento >= :desde";
      $p[':desde'] = $f['desde'] . ' 00:00:00';
    }
    if (!empty($f['hasta'])) {
      $donde[] = "r.Fecha_evento <= :hasta";
      $p[':hasta'] = $f['hasta'] . ' 23:59:59';
    }
    return [$donde ? 'WHERE ' . implode(' AND ', $donde) : '', $p];
  }

  public static function actividad(array $f, int $pagina, int $porPagina): array {
    $pdo = DB::getInstance();
    [$where, $p] = self::condicionesActividad($f);
    $from = "FROM Registro_actividad r LEFT JOIN Usuarios u ON u.Correo = r.Correo";

    $st = $pdo->prepare("SELECT COUNT(*) $from $where");
    $st->execute($p);
    $total = (int) $st->fetchColumn();

    $pagina = max(1, $pagina);
    $st = $pdo->prepare(
      "SELECT r.Id_registro, r.Correo, r.Tipo_evento, r.Fecha_evento, r.Ip_address, r.User_agent, u.Nombre, u.Apellido, u.Rol
       $from $where ORDER BY r.Fecha_evento DESC, r.Id_registro DESC
       LIMIT " . (int) $porPagina . " OFFSET " . (int) (($pagina - 1) * $porPagina)
    );
    $st->execute($p);
    $filas = $st->fetchAll(\PDO::FETCH_ASSOC);
    foreach ($filas as &$r) {
      $r['Dispositivo'] = self::dispositivo((string) $r['User_agent']);
    }
    unset($r);

    return ['filas' => $filas, 'total' => $total];
  }

  /** Todas las filas que cumplen el filtro (para el CSV), con tope de seguridad. */
  public static function actividadParaExportar(array $f, int $limite = 20000): array {
    [$where, $p] = self::condicionesActividad($f);
    $st = DB::getInstance()->prepare(
      "SELECT r.Fecha_evento, r.Correo, u.Nombre, u.Apellido, r.Tipo_evento, r.Ip_address, r.User_agent
       FROM Registro_actividad r LEFT JOIN Usuarios u ON u.Correo = r.Correo $where
       ORDER BY r.Fecha_evento DESC, r.Id_registro DESC LIMIT " . (int) $limite
    );
    $st->execute($p);
    return $st->fetchAll(\PDO::FETCH_ASSOC);
  }

  /** Resumen de los eventos que cumplen el filtro: total de inicios, cierres y usuarios distintos. */
  public static function resumenActividad(array $f): array {
    [$where, $p] = self::condicionesActividad($f);
    $st = DB::getInstance()->prepare(
      "SELECT
         SUM(CASE WHEN r.Tipo_evento = 'login'  THEN 1 ELSE 0 END) AS logins,
         SUM(CASE WHEN r.Tipo_evento = 'logout' THEN 1 ELSE 0 END) AS logouts,
         COUNT(DISTINCT r.Correo) AS usuarios
       FROM Registro_actividad r LEFT JOIN Usuarios u ON u.Correo = r.Correo $where"
    );
    $st->execute($p);
    $r = $st->fetch(\PDO::FETCH_ASSOC) ?: [];
    return ['logins' => (int) ($r['logins'] ?? 0), 'logouts' => (int) ($r['logouts'] ?? 0), 'usuarios' => (int) ($r['usuarios'] ?? 0)];
  }

  /** Inicios de sesión por día en los últimos $dias días (incluye los días sin actividad, con 0). */
  public static function loginsPorDia(int $dias = 14): array {
    $desde = date('Y-m-d 00:00:00', strtotime('-' . ($dias - 1) . ' days'));
    $st = DB::getInstance()->prepare(
      "SELECT DATE(Fecha_evento) AS dia, COUNT(*) AS n FROM Registro_actividad
       WHERE Tipo_evento = 'login' AND Fecha_evento >= :d GROUP BY DATE(Fecha_evento)"
    );
    $st->execute([':d' => $desde]);
    $mapa = [];
    foreach ($st->fetchAll(\PDO::FETCH_ASSOC) as $f) {
      $mapa[$f['dia']] = (int) $f['n'];
    }
    $serie = [];
    for ($i = $dias - 1; $i >= 0; $i--) {
      $d = date('Y-m-d', strtotime("-$i days"));
      $serie[] = ['dia' => $d, 'n' => $mapa[$d] ?? 0];
    }
    return $serie;
  }

  // ───────────────────────── Utilidades ─────────────────────────

  /** "Mozilla/5.0 (Windows NT 10.0…) Chrome/120…" → "Chrome · Windows" */
  public static function dispositivo(string $ua): string {
    if ($ua === '') {
      return '—';
    }
    $nav = 'Navegador desconocido';
    foreach (['Edg' => 'Edge', 'OPR' => 'Opera', 'Firefox' => 'Firefox', 'Chrome' => 'Chrome', 'Safari' => 'Safari'] as $clave => $nombre) {
      if (stripos($ua, $clave) !== false) { $nav = $nombre; break; }
    }
    $so = 'SO desconocido';
    foreach (['Windows' => 'Windows', 'Android' => 'Android', 'iPhone' => 'iOS', 'iPad' => 'iOS', 'Mac OS' => 'macOS', 'Linux' => 'Linux'] as $clave => $nombre) {
      if (stripos($ua, $clave) !== false) { $so = $nombre; break; }
    }
    return $nav . ' · ' . $so;
  }
}
