<?php
use App\core\Formato;

$iconosTipo = [
  'video'    => 'fa-solid fa-circle-play',
  'imagenes' => 'fa-solid fa-images',
  'chat'     => 'fa-solid fa-comments',
];
$tipoServicio = $servicio['Tipo_servicio'];
$urlVideo = BASE_PATH . '/video/ver?id=';
$primero = $videos[0] ?? null;
?>
<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row align-items-center">
        <div class="col-12">
          <h3 class="mb-0 servicio-titulo">
            <span class="servicio-titulo__icono"><i class="<?= $iconosTipo[$tipoServicio] ?? $iconosTipo['video'] ?>"></i></span>
            <?= htmlspecialchars($servicio['Nombre_servicio']) ?>
          </h3>
          <?php if (trim($servicio['Descripcion_servicio']) !== ''): ?>
            <p class="servicio-descripcion"><?= htmlspecialchars($servicio['Descripcion_servicio']) ?></p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <div class="app-content">
    <div class="container-fluid">

      <?php if (!empty($vistaPreviaAdmin)): ?>
        <div class="aviso-previa" role="note">
          <i class="fa-solid fa-eye"></i>
          Vista previa de administrador: así ven este servicio los usuarios suscritos.
        </div>
      <?php endif; ?>

      <?php if ($tipoServicio === 'video'): ?>

        <?php if ($primero): ?>
          <div class="servicio-video" data-url-base="<?= htmlspecialchars($urlVideo, ENT_QUOTES) ?>">
            <section class="servicio-video__principal" aria-label="Reproductor">
              <div class="video-marco">
                <video id="reproductor" controls controlsList="nodownload" playsinline preload="metadata"
                       src="<?= htmlspecialchars($urlVideo . (int) $primero['Id'], ENT_QUOTES) ?>"></video>
              </div>
              <div class="video-error" id="videoError" role="alert" hidden>
                No se pudo cargar este vídeo. Comprueba que tu suscripción sigue activa o inténtalo de nuevo.
              </div>
              <div class="video-info">
                <h4 id="videoTitulo"><?= htmlspecialchars($primero['Nombre_video']) ?></h4>
                <span id="videoMeta">
                  <?= Formato::duracion((int) $primero['Duracion_segundos']) ?> · <?= Formato::fecha($primero['Fecha_subida']) ?>
                </span>
              </div>
            </section>

            <aside class="servicio-video__lista" aria-label="Lista de vídeos">
              <h5>Vídeos <span><?= count($videos) ?></span></h5>
              <ol class="video-lista">
                <?php foreach ($videos as $i => $v): ?>
                  <li>
                    <button type="button" class="video-item<?= $i === 0 ? ' activo' : '' ?>"
                            data-id="<?= (int) $v['Id'] ?>"
                            data-nombre="<?= htmlspecialchars($v['Nombre_video'], ENT_QUOTES) ?>"
                            data-meta="<?= htmlspecialchars(Formato::duracion((int) $v['Duracion_segundos']) . ' · ' . Formato::fecha($v['Fecha_subida']), ENT_QUOTES) ?>"
                            <?= $i === 0 ? 'aria-current="true"' : '' ?>>
                      <span class="video-item__num"><?= $i + 1 ?></span>
                      <span class="video-item__txt">
                        <strong><?= htmlspecialchars($v['Nombre_video']) ?></strong>
                        <small><?= Formato::duracion((int) $v['Duracion_segundos']) ?></small>
                      </span>
                      <i class="fa-solid fa-play video-item__play" aria-hidden="true"></i>
                    </button>
                  </li>
                <?php endforeach; ?>
              </ol>
            </aside>
          </div>
        <?php else: ?>
          <div class="servicio-vacio">
            <i class="fa-solid fa-film"></i>
            <h4>Aún no hay vídeos</h4>
            <p>Cuando se publiquen vídeos de este servicio, aparecerán aquí.</p>
          </div>
        <?php endif; ?>

      <?php elseif ($tipoServicio === 'chat'): ?>

        <div class="servicio-vacio">
          <i class="fa-solid fa-comments"></i>
          <h4>Tu servicio funciona por chat</h4>
          <p>Escríbenos y te respondemos en directo desde la burbuja de mensajería.</p>
          <button type="button" class="btnRegistrarse" data-abrir-chat>
            <i class="fa-solid fa-comments"></i> Abrir el chat
          </button>
        </div>

      <?php else: ?>

        <div class="servicio-vacio">
          <i class="fa-solid fa-images"></i>
          <h4>Próximamente</h4>
          <p>Estamos preparando la galería de imágenes de este servicio.</p>
        </div>

      <?php endif; ?>

    </div>
  </div>
</main>
