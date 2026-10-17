<?php
/**
 * PusherClient.php
 * ---------------------------------------------------------
 * Cliente muy ligero para Pusher Channels usando cURL, en la misma
 * línea que stripeModelo: no hace falta instalar nada con Composer.
 *
 * Hace dos cosas:
 *   1) trigger():   avisa a Pusher de un evento (mensaje nuevo, chat cerrado...)
 *                   y Pusher lo reparte a los navegadores suscritos al canal.
 *   2) autorizar(): firma la suscripción a canales privados / de presencia
 *                   (el navegador la pide a /chat/pusher_auth).
 *
 * Las claves vienen de las constantes PUSHER_* definidas en index.php.
 * Opcionales (para pruebas o Pusher autoalojado tipo Soketi):
 *   define('PUSHER_HOST', 'localhost:6001');  define('PUSHER_SCHEME', 'http');
 * ---------------------------------------------------------
 */

namespace App\core;

class PusherClient
{
  private static ?PusherClient $instancia = null;

  public static function get(): self
  {
    if (self::$instancia === null) {
      self::$instancia = new self();
    }
    return self::$instancia;
  }

  /** true si las claves ya no son los marcadores "TU_..." de index.php */
  public static function configurado(): bool
  {
    foreach ([PUSHER_APP_ID, PUSHER_KEY, PUSHER_SECRET, PUSHER_CLUSTER] as $valor) {
      if ($valor === '' || strpos($valor, 'TU_') === 0) {
        return false;
      }
    }
    return true;
  }

  /**
   * Publica un evento en un canal.
   * $excluirSocketId: el socket del navegador que lo originó, para que Pusher
   * NO se lo devuelva (ese navegador ya pintó el mensaje con la respuesta HTTP).
   * Devuelve false si falla; quien llama decide si le importa (el mensaje ya
   * está guardado en la base de datos, así que no se pierde).
   */
  public function trigger(string $canal, string $evento, array $datos, ?string $excluirSocketId = null): bool
  {
    if (!self::configurado()) {
      return false;
    }

    $cuerpo = [
      'name'     => $evento,
      'channels' => [$canal],
      'data'     => json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
    ];
    if ($excluirSocketId !== null && preg_match('/^\d+\.\d+$/', $excluirSocketId)) {
      $cuerpo['socket_id'] = $excluirSocketId;
    }
    $json = json_encode($cuerpo, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    $ruta   = '/apps/' . PUSHER_APP_ID . '/events';
    $params = [
      'auth_key'       => PUSHER_KEY,
      'auth_timestamp' => (string) time(),
      'auth_version'   => '1.0',
      'body_md5'       => md5($json),
    ];
    ksort($params);
    $query = http_build_query($params);

    // Firma: HMAC-SHA256 de "POST\n<ruta>\n<query ordenada>" con el secret
    $firma = hash_hmac('sha256', "POST\n{$ruta}\n{$query}", PUSHER_SECRET);

    $esquema = defined('PUSHER_SCHEME') ? PUSHER_SCHEME : 'https';
    $host    = defined('PUSHER_HOST') ? PUSHER_HOST : 'api-' . PUSHER_CLUSTER . '.pusher.com';
    $url     = "{$esquema}://{$host}{$ruta}?{$query}&auth_signature={$firma}";

    $ch = curl_init($url);
    curl_setopt_array($ch, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST           => true,
      CURLOPT_POSTFIELDS     => $json,
      CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
      CURLOPT_CONNECTTIMEOUT => 3,
      CURLOPT_TIMEOUT        => 5,
    ]);
    curl_exec($ch);
    $codigo = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $codigo === 200;
  }

  /**
   * Firma la suscripción de un navegador a un canal private-* o presence-*.
   * Para canales de presencia pasa $presencia = ['user_id' => ..., 'user_info' => [...]].
   * Devuelve el array que el navegador espera (se imprime como JSON).
   */
  public function autorizar(string $socketId, string $canal, ?array $presencia = null): array
  {
    $aFirmar   = $socketId . ':' . $canal;
    $respuesta = [];

    if ($presencia !== null) {
      $channelData = json_encode($presencia, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
      $aFirmar .= ':' . $channelData;
      $respuesta['channel_data'] = $channelData;
    }

    $respuesta['auth'] = PUSHER_KEY . ':' . hash_hmac('sha256', $aFirmar, PUSHER_SECRET);
    return $respuesta;
  }
}
