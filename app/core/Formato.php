<?php

namespace App\core;

/** Pequeños formateadores para las vistas. */
class Formato {

  /** 75 → "1:15" · 3725 → "1:02:05" */
  public static function duracion(int $segundos): string {
    $segundos = max(0, $segundos);
    $h = intdiv($segundos, 3600);
    $m = intdiv($segundos % 3600, 60);
    $s = $segundos % 60;
    return $h > 0 ? sprintf('%d:%02d:%02d', $h, $m, $s) : sprintf('%d:%02d', $m, $s);
  }

  /** 1536 → "1,5 KB" · 5242880 → "5 MB" */
  public static function tamano(?int $bytes): string {
    if ($bytes === null) {
      return '—';
    }
    $unidades = ['B', 'KB', 'MB', 'GB'];
    $i = 0;
    $n = (float) $bytes;
    while ($n >= 1024 && $i < count($unidades) - 1) {
      $n /= 1024;
      $i++;
    }
    $texto = number_format($n, $i === 0 ? 0 : 1, ',', '');
    if (strpos($texto, ',') !== false) {            // "5,0" → "5" (pero "10" bytes se queda en "10")
      $texto = rtrim(rtrim($texto, '0'), ',');
    }
    return $texto . ' ' . $unidades[$i];
  }

  public static function fecha(string $fechaSql): string {
    $t = strtotime($fechaSql);
    return $t ? date('d/m/Y', $t) : '';
  }
}
