<?php
use App\core\Formato;

$extensiones = '.' . implode(', .', $config['extensiones']);
?>
<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <?php $adminSeccion = 'servicios'; $heroTitulo = ''; include __DIR__ . '/cabecera_seccion.php'; ?>
      <div class="row align-items-center">
        <div class="col-sm-8">
          <a class="admin-volver" href="<?= BASE_PATH . '/admin/servicios' ?>">
            <i class="fa-solid fa-arrow-left"></i> Volver a Administrar servicios
          </a>
          <h3 class="mb-0 mt-2">Vídeos · <?= htmlspecialchars($servicio['Nombre_servicio']) ?></h3>
          <small class="admin-subtitulo">
            Servicio <code><?= htmlspecialchars($servicio['Id_servicio']) ?></code> ·
            <?= count($videos) ?> vídeo<?= count($videos) === 1 ? '' : 's' ?>.
            Solo los usuarios con este servicio contratado podrán verlos.
          </small>
        </div>
      </div>
    </div>
  </div>

  <div class="app-content">
    <div class="container-fluid">

      <!-- ═════════ Subir un vídeo ═════════ -->
      <section class="admin-panel" aria-labelledby="tituloSubida">
        <h4 id="tituloSubida"><i class="fa-solid fa-cloud-arrow-up"></i> Subir un vídeo</h4>

        <form id="formSubida" novalidate>
          <div class="admin-error" id="subidaError" role="alert" hidden></div>

          <div class="row g-3">
            <div class="col-12">
              <label class="form-label" for="vidArchivo">Archivo de vídeo *</label>
              <input type="file" class="form-control" id="vidArchivo" accept=".mp4,.webm,.ogv,video/mp4,video/webm,video/ogg" aria-describedby="vidArchivoAyuda">
              <div class="form-text" id="vidArchivoAyuda">
                Formatos: <?= htmlspecialchars($extensiones) ?> · máximo <?= htmlspecialchars(Formato::tamano($config['maxBytes'])) ?>.
                Los vídeos de iPhone (.mov) hay que exportarlos a .mp4.
              </div>
            </div>

            <div class="col-md-8">
              <label class="form-label" for="vidNombre">Nombre del vídeo *</label>
              <input type="text" class="form-control" id="vidNombre" maxlength="200" autocomplete="off">
            </div>
            <div class="col-md-4">
              <label class="form-label" for="vidDuracion">Duración (segundos) *</label>
              <input type="number" class="form-control" id="vidDuracion" min="1" max="<?= (int) $config['maxDuracion'] ?>" inputmode="numeric" aria-describedby="vidDuracionAyuda">
              <div class="form-text" id="vidDuracionAyuda">Se detecta sola al elegir el archivo.</div>
            </div>
          </div>

          <div class="subida-progreso" id="subidaProgreso" hidden>
            <div class="progress" role="progressbar" aria-label="Progreso de la subida" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" id="subidaBarraCont">
              <div class="progress-bar" id="subidaBarra" style="width: 0%"></div>
            </div>
            <div class="subida-estado" id="subidaEstado" aria-live="polite"></div>
          </div>

          <div class="subida-botones">
            <button type="submit" class="btnRegistrarse" id="subidaEnviar">
              <i class="fa-solid fa-upload"></i> Subir vídeo
            </button>
            <button type="button" class="btn btn-outline-secondary" id="subidaCancelar" hidden>Cancelar subida</button>
          </div>
        </form>
      </section>

      <!-- ═════════ Lista ═════════ -->
      <section class="admin-panel" aria-labelledby="tituloLista">
        <h4 id="tituloLista"><i class="fa-solid fa-film"></i> Vídeos del servicio <span class="admin-contador"><?= count($videos) ?></span></h4>

        <?php if (!$videos): ?>
          <p class="admin-vacio">Todavía no hay vídeos. Sube el primero con el formulario de arriba.</p>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table align-middle admin-tabla">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Duración</th>
                  <th scope="col">Tamaño</th>
                  <th scope="col">Subido</th>
                  <th scope="col" class="text-end">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($videos as $i => $v): ?>
                  <tr data-id="<?= (int) $v['Id'] ?>" data-nombre="<?= htmlspecialchars($v['Nombre_video'], ENT_QUOTES) ?>">
                    <td><?= $i + 1 ?></td>
                    <td class="admin-tabla__nombre">
                      <?= htmlspecialchars($v['Nombre_video']) ?>
                      <?php if ($v['bytes'] === null): ?>
                        <span class="chip-admin chip-admin--aviso" title="El archivo ya no está en el servidor. Elimina este registro y vuelve a subir el vídeo.">
                          <i class="fa-solid fa-triangle-exclamation"></i> Archivo no encontrado
                        </span>
                      <?php endif; ?>
                    </td>
                    <td><?= Formato::duracion((int) $v['Duracion_segundos']) ?></td>
                    <td><?= htmlspecialchars(Formato::tamano($v['bytes'])) ?></td>
                    <td><?= Formato::fecha($v['Fecha_subida']) ?></td>
                    <td class="text-end admin-tabla__acciones">
                      <?php if ($v['bytes'] !== null): ?>
                        <button type="button" class="btn-admin" data-accion="ver"><i class="fa-solid fa-play"></i> Ver</button>
                      <?php endif; ?>
                      <button type="button" class="btn-admin" data-accion="renombrar"><i class="fa-solid fa-pen"></i> Renombrar</button>
                      <button type="button" class="btn-admin btn-admin--peligro" data-accion="eliminar"><i class="fa-solid fa-trash"></i> Eliminar</button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </section>

    </div>
  </div>
</main>

<!-- ═════════ Modal: vista previa ═════════ -->
<div class="modal fade" id="modalVistaPrevia" tabindex="-1" aria-labelledby="vistaPreviaTitulo" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="vistaPreviaTitulo">Vista previa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body p-0">
        <div class="video-marco" style="border-radius: 0; box-shadow: none;">
          <video id="vistaPreviaVideo" controls controlsList="nodownload" playsinline preload="metadata"></video>
        </div>
      </div>
    </div>
  </div>
</div>
