<?php
/* Funciones de ayuda para las vistas de administración (panel, usuarios, actividad). */

if (!function_exists('adm_e')) {
  function adm_e($v): string { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); }

  /** 2026-11-12 16:05:00 → 12/11/2026 16:05 */
  function adm_fecha(?string $f): string {
    if (!$f) return '—';
    $t = strtotime($f);
    return $t ? date('d/m/Y H:i', $t) : '—';
  }

  /** Tiempo relativo: "hace 5 min", "hace 3 h", "hace 2 días"; si pasó más de un mes, la fecha. */
  function adm_hace(?string $f): string {
    if (!$f) return 'Nunca';
    $t = strtotime($f);
    if (!$t) return '—';
    $d = time() - $t;
    if ($d < 0)        return adm_fecha($f);
    if ($d < 60)       return 'ahora mismo';
    if ($d < 3600)     return 'hace ' . floor($d / 60) . ' min';
    if ($d < 86400)    return 'hace ' . floor($d / 3600) . ' h';
    if ($d < 2592000)  { $n = (int) floor($d / 86400); return 'hace ' . $n . ($n === 1 ? ' día' : ' días'); }
    return date('d/m/Y', $t);
  }

  function adm_inicial(string $nombre, string $correo = ''): string {
    $base = trim($nombre) !== '' ? $nombre : $correo;
    return mb_strtoupper(mb_substr($base, 0, 1));
  }

  /** Mismo color de avatar que usa el resto de la zona privada (md5 de nombre + apellido). */
  function adm_color(string $nombre, string $apellido): string {
    return '#' . substr(md5($nombre . $apellido), 0, 6);
  }

  function adm_rol_clase(string $rol): string {
    return ['Superusuario' => 'adm-rol--super', 'Administrador' => 'adm-rol--admin'][$rol] ?? 'adm-rol--user';
  }

  /** Enlace conservando los filtros actuales y cambiando lo que se indique. */
  function adm_url(string $ruta, array $filtros, array $extra = []): string {
    $q = array_filter($extra + $filtros, fn($v) => $v !== '' && $v !== null);
    return BASE_PATH . $ruta . ($q ? '?' . http_build_query($q) : '');
  }
}
