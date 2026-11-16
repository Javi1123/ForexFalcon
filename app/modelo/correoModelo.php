<?php

namespace App\modelo;

/**
 * Correos de ForexFalcon.
 *
 * Todos comparten la misma plantilla HTML (cabecera de marca, tarjeta de contenido y pie) y se envían como
 * multipart/alternative: versión HTML + versión de texto plano, para los clientes que no muestran HTML.
 * El HTML está hecho con tablas y estilos en línea, que es lo único que soportan de forma fiable Gmail,
 * Outlook y compañía.
 *
 * Opcional: define('URL_SITIO', 'https://tu-dominio.com') en index.php para cambiar el dominio de los enlaces.
 */
class correoModelo {

  private const REMITENTE_NOMBRE = 'ForexFalcon';
  private const REMITENTE_CORREO = 'noreply@tecnologiayformacion.com';
  private const CONTACTO_CORREO  = 'forexfalcon@gmail.com';
  private const CONTACTO_WEB     = 'www.forexfalcon.com';

  // Colores de marca
  private const AZUL     = '#070D25';
  private const NARANJA  = '#FB8E37';
  private const VERDE    = '#91DD4E';
  private const FONDO    = '#EEF1F7';
  private const TEXTO    = '#2B3350';
  private const SUAVE    = '#6B7490';

  /////////////////////////////////
  // CORREO MANDAR DE RESGISTRO
  /////////////////////////////////

  public static function correo_registro($cNombre, $cCorreo){
    $nombre = trim((string) $cNombre);
    $url    = self::url('/acciones?tipo=inicio');

    $titulo = 'Gracias por registrarte en ForexFalcon';

    $html  = self::titulo('¡Te damos la bienvenida' . ($nombre !== '' ? ', ' . self::e($nombre) : '') . '!');
    $html .= self::parrafo('Tu cuenta en <strong>ForexFalcon</strong> se ha creado correctamente. Ya formas parte de una comunidad pensada para acompañarte paso a paso en el trading.');
    $html .= self::parrafo('Desde tu área privada podrás:');
    $html .= self::lista([
      ['Descubrir nuestros servicios', 'Bots, copy trading y análisis semanal, con el detalle de qué incluye cada uno.'],
      ['Hablar con nosotros', 'Un chat directo con el equipo y WhatsApp para resolver cualquier duda.'],
      ['Gestionar tus suscripciones', 'Consulta lo que tienes contratado y accede a tus contenidos.'],
    ]);
    $html .= self::boton('Iniciar sesión', $url);
    $html .= self::nota('Si tú no has creado esta cuenta, puedes ignorar este mensaje o escribirnos a ' . self::e(self::CONTACTO_CORREO) . '.');

    $texto = "¡Te damos la bienvenida" . ($nombre !== '' ? ", $nombre" : '') . "!\n\n"
      . "Tu cuenta en ForexFalcon se ha creado correctamente. Ya formas parte de una comunidad pensada para acompañarte paso a paso en el trading.\n\n"
      . "Desde tu área privada podrás:\n"
      . "- Descubrir nuestros servicios: bots, copy trading y análisis semanal.\n"
      . "- Hablar con nosotros: chat directo con el equipo y WhatsApp.\n"
      . "- Gestionar tus suscripciones y acceder a tus contenidos.\n\n"
      . "Iniciar sesión: $url\n\n"
      . "Si tú no has creado esta cuenta, puedes ignorar este mensaje.";

    return self::enviar($cCorreo, $titulo, self::plantilla($titulo, 'Tu cuenta en ForexFalcon ya está lista.', $html), $texto);
  }

  //////////////////////////////////////////////////////
  // CORREO MANDAR PARA NOSOTROS DEL REGISTRO
  //////////////////////////////////////////////////////

  /**
   * Aviso interno. $c_DatosAlumno es HTML ya preparado por quien llama (no se escapa).
   */
  public static function correo_nosotros($c_Asunto, $c_DatosAlumno){
    $cPara = 'javierdiazsoriano1@gmail.com';

    $html  = self::titulo(self::e($c_Asunto));
    $html .= '<div style="font-size:15px;line-height:1.65;color:' . self::TEXTO . ';">' . $c_DatosAlumno . '</div>';

    $texto = $c_Asunto . "\n\n" . trim(html_entity_decode(strip_tags(preg_replace('#<br\s*/?>|</(p|div|tr|li)>#i', "\n", (string) $c_DatosAlumno)), ENT_QUOTES, 'UTF-8'));

    return self::enviar($cPara, $c_Asunto, self::plantilla($c_Asunto, 'Nuevo aviso de ForexFalcon.', $html), $texto);
  }

  //////////////////////////////////////////////////////
  // CORREO MANDAR DE FORMULARIO DESCUENTO
  //////////////////////////////////////////////////////

  public static function correo_descuento($cCorreo, $codigo){
    $url    = self::url('/tienda');
    $titulo = '¡Tu código de descuento exclusivo en ForexFalcon te espera!';

    $html  = self::titulo('Tu descuento del 10% ya está aquí');
    $html .= self::parrafo('Sabemos que dar el primer paso hacia la libertad financiera es una gran decisión. Por eso queremos acompañarte con un beneficio especial en tu <strong>primera suscripción</strong>.');
    $html .= self::codigo((string) $codigo, '10% de descuento en tu primera suscripción');
    $html .= self::parrafo('<strong>¿Cómo canjearlo?</strong>');
    $html .= self::pasos([
      'Accede a nuestra plataforma y entra en la sección de servicios.',
      'Elige el servicio que mejor se adapte a tu objetivo.',
      'Introduce el código en el momento del pago y disfruta de tu descuento.',
    ]);
    $html .= self::boton('Ver servicios', $url);
    $html .= self::parrafo('En ForexFalcon no solo obtienes herramientas tecnológicas (bots, copy trading y análisis semanal), sino también el respaldo humano de un mentor 1:1 y una comunidad colaborativa que te guiará paso a paso. Tu constancia y nuestro acompañamiento son la fórmula para resultados sostenibles.');
    $html .= self::nota('El código es de un solo uso y se aplica a la primera suscripción. ¡Nos vemos dentro!');

    $texto = "Tu descuento del 10% ya está aquí\n\n"
      . "Sabemos que dar el primer paso hacia la libertad financiera es una gran decisión. Por eso queremos acompañarte con un beneficio especial en tu primera suscripción.\n\n"
      . "CÓDIGO DE DESCUENTO: $codigo\n"
      . "DESCUENTO APLICADO: 10% en tu primera suscripción.\n\n"
      . "¿Cómo canjearlo?\n"
      . "1. Accede a nuestra plataforma y entra en la sección de servicios.\n"
      . "2. Elige el servicio que mejor se adapte a tu objetivo.\n"
      . "3. Introduce el código en el momento del pago y disfruta de tu descuento.\n\n"
      . "Ver servicios: $url\n\n"
      . "En ForexFalcon no solo obtienes herramientas tecnológicas (bots, copy trading y análisis semanal), sino también el respaldo humano de un mentor 1:1 y una comunidad colaborativa que te guiará paso a paso.\n\n"
      . "El código es de un solo uso. ¡Nos vemos dentro!";

    return self::enviar($cCorreo, $titulo, self::plantilla($titulo, 'Tu código ' . $codigo . ': 10% en tu primera suscripción.', $html), $texto);
  }

  //////////////////////////////////////////////////////
  // CORREO RESTABLECER CONTRASEÑA
  //////////////////////////////////////////////////////

  /** $enlace es la URL completa con el token; solo se manda por correo, nunca se muestra en la web. */
  public static function correo_restablecer($cCorreo, $cNombre, string $enlace, int $minutos = 60){
    $nombre = trim((string) $cNombre);
    $titulo = 'Restablece tu contraseña de ForexFalcon';

    $html  = self::titulo('Restablece tu contraseña');
    $html .= self::parrafo('Hola' . ($nombre !== '' ? ' <strong>' . self::e($nombre) . '</strong>' : '') . ', hemos recibido una solicitud para cambiar la contraseña de tu cuenta de ForexFalcon.');
    $html .= self::parrafo('Pulsa el botón para elegir una contraseña nueva. Por seguridad, el enlace <strong>caduca en ' . (int) $minutos . ' minutos</strong> y solo se puede usar una vez.');
    $html .= self::boton('Crear nueva contraseña', $enlace);
    $html .= self::nota('Si el botón no funciona, copia y pega esta dirección en tu navegador:<br><span style="word-break:break-all;color:' . self::TEXTO . ';">' . self::e($enlace) . '</span>');
    $html .= self::nota('<strong>¿No has sido tú?</strong> Ignora este mensaje: tu contraseña actual no cambia mientras no uses el enlace. Si te llegan estos correos sin pedirlos, escríbenos a ' . self::e(self::CONTACTO_CORREO) . '.');

    $texto = "Restablece tu contraseña

"
      . "Hola" . ($nombre !== '' ? " $nombre" : '') . ", hemos recibido una solicitud para cambiar la contraseña de tu cuenta de ForexFalcon.

"
      . "Abre este enlace para elegir una contraseña nueva (caduca en $minutos minutos y solo se puede usar una vez):
$enlace

"
      . "¿No has sido tú? Ignora este mensaje: tu contraseña actual no cambia mientras no uses el enlace.";

    return self::enviar($cCorreo, $titulo, self::plantilla($titulo, 'Enlace para elegir una contraseña nueva (caduca en ' . (int) $minutos . ' minutos).', $html), $texto);
  }

  /** Aviso de que la contraseña se ha cambiado (para detectar cambios que no hizo el dueño de la cuenta). */
  public static function correo_contrasena_cambiada($cCorreo, $cNombre){
    $nombre = trim((string) $cNombre);
    $titulo = 'Tu contraseña de ForexFalcon se ha cambiado';
    $cuando = date('d/m/Y \a \l\a\s H:i');
    $url    = self::url('/acciones?tipo=inicio');

    $html  = self::titulo('Tu contraseña se ha cambiado');
    $html .= self::parrafo('Hola' . ($nombre !== '' ? ' <strong>' . self::e($nombre) . '</strong>' : '') . ', te confirmamos que la contraseña de tu cuenta se cambió el ' . self::e($cuando) . '.');
    $html .= self::boton('Iniciar sesión', $url);
    $html .= self::nota('<strong>¿No has sido tú?</strong> Restablece tu contraseña de inmediato desde «¿Has olvidado tu contraseña?» y escríbenos a ' . self::e(self::CONTACTO_CORREO) . '.');

    $texto = "Tu contraseña se ha cambiado

"
      . "Hola" . ($nombre !== '' ? " $nombre" : '') . ", te confirmamos que la contraseña de tu cuenta se cambió el $cuando.

"
      . "Iniciar sesión: $url

"
      . "¿No has sido tú? Restablece tu contraseña de inmediato y escríbenos a " . self::CONTACTO_CORREO . ".";

    return self::enviar($cCorreo, $titulo, self::plantilla($titulo, 'Te confirmamos el cambio de contraseña de tu cuenta.', $html), $texto);
  }

  //////////////////////////////////////////////////////
  // PLANTILLA Y ENVÍO
  //////////////////////////////////////////////////////

  /** Marco común: cabecera de marca, tarjeta con el contenido y pie. */
  private static function plantilla(string $titulo, string $preheader, string $contenido): string {
    $azul = self::AZUL; $naranja = self::NARANJA; $fondo = self::FONDO; $suave = self::SUAVE;
    $tituloE = self::e($titulo);
    $pre     = self::e($preheader);
    $correo  = self::e(self::CONTACTO_CORREO);
    $web     = self::e(self::CONTACTO_WEB);
    $webUrl  = self::e('https://' . self::CONTACTO_WEB);
    $anio    = date('Y');
    $fuente  = "-apple-system,'Segoe UI',Roboto,Helvetica,Arial,sans-serif";

    return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="color-scheme" content="light only">
<title>{$tituloE}</title>
</head>
<body style="margin:0;padding:0;background:{$fondo};">
<div style="display:none;max-height:0;overflow:hidden;opacity:0;color:{$fondo};font-size:1px;line-height:1px;">{$pre}&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;</div>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:{$fondo};">
  <tr>
    <td align="center" style="padding:28px 12px;">
      <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;">
        <tr>
          <td align="center" style="background:{$azul};border-radius:16px 16px 0 0;padding:30px 24px 26px;border-bottom:4px solid {$naranja};">
            <span style="font-family:{$fuente};font-size:28px;font-weight:800;letter-spacing:.5px;color:#FFFFFF;">Forex<span style="color:{$naranja};">Falcon</span></span>
          </td>
        </tr>
        <tr>
          <td style="background:#FFFFFF;padding:34px 32px 30px;font-family:{$fuente};">
            {$contenido}
          </td>
        </tr>
        <tr>
          <td style="background:#FFFFFF;padding:0 32px 30px;font-family:{$fuente};">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top:1px solid #E3E7F1;">
              <tr><td style="padding-top:20px;font-size:15px;line-height:1.6;color:{$azul};">Gracias,<br><strong>El equipo de ForexFalcon</strong></td></tr>
            </table>
          </td>
        </tr>
        <tr>
          <td align="center" style="background:{$azul};border-radius:0 0 16px 16px;padding:22px 24px;font-family:{$fuente};font-size:13px;line-height:1.7;color:#AEB6D3;">
            <a href="{$webUrl}" style="color:#FFFFFF;text-decoration:none;font-weight:600;">{$web}</a><br>
            <a href="mailto:{$correo}" style="color:#AEB6D3;text-decoration:none;">{$correo}</a>
          </td>
        </tr>
        <tr>
          <td align="center" style="padding:16px 12px 0;font-family:{$fuente};font-size:12px;line-height:1.6;color:{$suave};">
            Has recibido este correo porque utilizaste ForexFalcon.<br>© {$anio} ForexFalcon
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
HTML;
  }

  private static function fuente(): string {
    return "-apple-system,'Segoe UI',Roboto,Helvetica,Arial,sans-serif";
  }

  private static function titulo(string $htmlSeguro): string {
    return '<h1 style="margin:0 0 16px;font-family:' . self::fuente() . ';font-size:24px;line-height:1.3;font-weight:800;color:' . self::AZUL . ';">' . $htmlSeguro . '</h1>';
  }

  private static function parrafo(string $htmlSeguro): string {
    return '<p style="margin:0 0 16px;font-family:' . self::fuente() . ';font-size:15px;line-height:1.7;color:' . self::TEXTO . ';">' . $htmlSeguro . '</p>';
  }

  private static function nota(string $htmlSeguro): string {
    return '<p style="margin:22px 0 0;font-family:' . self::fuente() . ';font-size:13px;line-height:1.6;color:' . self::SUAVE . ';">' . $htmlSeguro . '</p>';
  }

  /** Botón "a prueba de Outlook": es un enlace dentro de una celda con fondo. */
  private static function boton(string $texto, string $url): string {
    return '<table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin:24px auto;">'
      . '<tr><td align="center" bgcolor="' . self::NARANJA . '" style="border-radius:999px;">'
      . '<a href="' . self::e($url) . '" target="_blank" style="display:inline-block;padding:14px 34px;font-family:' . self::fuente() . ';font-size:16px;font-weight:700;color:' . self::AZUL . ';text-decoration:none;border-radius:999px;">'
      . self::e($texto) . '</a></td></tr></table>';
  }

  /** Lista de ventajas: [[título, descripción], ...] */
  private static function lista(array $items): string {
    $f = self::fuente();
    $h = '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0 0 8px;">';
    foreach ($items as [$t, $d]) {
      $h .= '<tr>'
        . '<td width="30" valign="top" style="padding:6px 0;"><div style="width:22px;height:22px;line-height:22px;border-radius:50%;background:' . self::VERDE . ';color:' . self::AZUL . ';text-align:center;font-family:' . $f . ';font-size:13px;font-weight:800;">&#10003;</div></td>'
        . '<td valign="top" style="padding:6px 0;font-family:' . $f . ';font-size:15px;line-height:1.6;color:' . self::TEXTO . ';"><strong style="color:' . self::AZUL . ';">' . self::e($t) . '</strong><br>' . self::e($d) . '</td>'
        . '</tr>';
    }
    return $h . '</table>';
  }

  /** Pasos numerados. */
  private static function pasos(array $items): string {
    $f = self::fuente();
    $h = '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0 0 8px;">';
    foreach (array_values($items) as $i => $t) {
      $h .= '<tr>'
        . '<td width="34" valign="top" style="padding:6px 0;"><div style="width:24px;height:24px;line-height:24px;border-radius:50%;background:' . self::AZUL . ';color:#FFFFFF;text-align:center;font-family:' . $f . ';font-size:13px;font-weight:700;">' . ($i + 1) . '</div></td>'
        . '<td valign="top" style="padding:6px 0;font-family:' . $f . ';font-size:15px;line-height:1.6;color:' . self::TEXTO . ';">' . self::e($t) . '</td>'
        . '</tr>';
    }
    return $h . '</table>';
  }

  /** Caja destacada con el código de descuento. */
  private static function codigo(string $codigo, string $detalle): string {
    return '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:6px 0 22px;">'
      . '<tr><td align="center" style="background:#FFF6EC;border:2px dashed ' . self::NARANJA . ';border-radius:14px;padding:22px 16px;">'
      . '<div style="font-family:' . self::fuente() . ';font-size:12px;letter-spacing:1.5px;text-transform:uppercase;color:' . self::SUAVE . ';margin-bottom:8px;">Tu código de descuento</div>'
      . '<div style="font-family:\'Courier New\',Courier,monospace;font-size:30px;font-weight:800;letter-spacing:3px;color:' . self::AZUL . ';">' . self::e($codigo) . '</div>'
      . '<div style="font-family:' . self::fuente() . ';font-size:14px;color:' . self::TEXTO . ';margin-top:10px;">' . self::e($detalle) . '</div>'
      . '</td></tr></table>';
  }

  /** Escapa para HTML. */
  private static function e($v): string {
    return htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
  }

  /**
   * URL absoluta del sitio. Usa SITE_URL de index.php (que ya incluye la ruta base, p. ej. http://localhost/forexfalcon)
   * para que los enlaces de los correos funcionen igual en local y en producción.
   * Si no existe, recurre a URL_SITIO / dominio por defecto + BASE_PATH.
   */
  private static function url(string $ruta): string {
    if (defined('SITE_URL') && SITE_URL !== '') {
      return rtrim(SITE_URL, '/') . $ruta;
    }
    $dominio = defined('URL_SITIO') ? URL_SITIO : (getenv('URL_SITIO') ?: 'https://' . self::CONTACTO_WEB);
    $base    = defined('BASE_PATH') ? BASE_PATH : '';
    return rtrim($dominio, '/') . $base . $ruta;
  }

  /** Quita saltos de línea de un valor que va a una cabecera (evita inyección de cabeceras). */
  private static function limpiarCabecera(string $v): string {
    return trim(preg_replace('/[\r\n]+/', ' ', $v));
  }

  /** Envía un correo multipart/alternative (texto plano + HTML). */
  private static function enviar($para, string $asunto, string $html, string $texto): bool {
    $para = self::limpiarCabecera((string) $para);
    if (!filter_var($para, FILTER_VALIDATE_EMAIL)) {
      return false;
    }
    $asunto = mb_encode_mimeheader(self::limpiarCabecera($asunto), 'UTF-8', 'B', "\r\n");
    $limite = 'ff_' . bin2hex(random_bytes(12));

    $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
    $cabeceras .= 'From: ' . self::REMITENTE_NOMBRE . ' <' . self::REMITENTE_CORREO . '>' . "\r\n";
    $cabeceras .= 'Reply-To: ' . self::CONTACTO_CORREO . "\r\n";
    $cabeceras .= 'Content-Type: multipart/alternative; boundary="' . $limite . '"' . "\r\n";

    $cuerpo  = '--' . $limite . "\r\n";
    $cuerpo .= 'Content-Type: text/plain; charset=UTF-8' . "\r\n";
    $cuerpo .= 'Content-Transfer-Encoding: base64' . "\r\n\r\n";
    $cuerpo .= chunk_split(base64_encode($texto)) . "\r\n";
    $cuerpo .= '--' . $limite . "\r\n";
    $cuerpo .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";
    $cuerpo .= 'Content-Transfer-Encoding: base64' . "\r\n\r\n";
    $cuerpo .= chunk_split(base64_encode($html)) . "\r\n";
    $cuerpo .= '--' . $limite . '--';

    return mail($para, $asunto, $cuerpo, $cabeceras);
  }
}
