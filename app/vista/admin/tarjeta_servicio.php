<?php
/**
 * Tarjeta de un servicio en el panel de administración (misma tarjeta que la tienda + botones de gestión).
 * Variables: $s (servicio), $archivado (bool), $sync (códigos presentes en la BD).
 */
$codigo = strtoupper($s['id_servicio']);
$enBd = $codigo !== '' && isset($sync[$codigo]);
$etiquetasTipo = ['unico' => 'Pago único', 'semanal' => 'Semanal', 'mensual' => 'Mensual', 'anual' => 'Anual'];
$datos = $s + ['en_bd' => $enBd];
$flagsJson = JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
?>
<div class="servicio-card servicio-card--admin<?= $archivado ? ' servicio-card--archivado' : '' ?>"
     data-servicio="<?= htmlspecialchars(json_encode($datos, $flagsJson), ENT_QUOTES) ?>">

  <div class="servicio-card__top">
    <div class="servicio-card__icon">
      <i class="<?= htmlspecialchars($s['icono'], ENT_QUOTES) ?>"></i>
    </div>
    <div class="servicio-card__chips">
      <span class="chip-admin" title="Código del servicio (id_servicio)">
        <?= $codigo !== '' ? htmlspecialchars($codigo) : 'Sin código' ?>
      </span>
      <span class="chip-admin chip-admin--suave"><?= htmlspecialchars($etiquetasTipo[$s['tipo']] ?? $s['tipo']) ?></span>
      <?php if ($archivado): ?>
        <span class="chip-admin chip-admin--gris">Archivado</span>
      <?php endif; ?>
      <?php if (!$enBd): ?>
        <span class="chip-admin chip-admin--aviso" title="Este servicio no está en tu tabla Servicios: una compra fallaría al guardar la suscripción.">
          <i class="fa-solid fa-triangle-exclamation"></i> Sin sincronizar
        </span>
      <?php endif; ?>
    </div>
  </div>

  <h3><?= htmlspecialchars($s['nombre']) ?></h3>
  <p class="servicio-card__desc"><?= htmlspecialchars($s['descripcion_corta']) ?></p>
  <div class="servicio-card__precio">
    <?= htmlspecialchars($s['precio']) ?>
    <span class="periodo"><?= htmlspecialchars($s['periodo']) ?></span>
  </div>

  <div class="servicio-card__admin">
    <?php if ($archivado): ?>
      <button type="button" class="btn-admin btn-admin--principal" data-accion="restaurar">
        <i class="fa-solid fa-rotate-left"></i> Restaurar
      </button>
    <?php else: ?>
      <button type="button" class="btn-admin btn-admin--principal" data-accion="editar">
        <i class="fa-solid fa-pen"></i> Editar
      </button>
      <button type="button" class="btn-admin" data-accion="precio">
        <i class="fa-solid fa-tag"></i> Precio
      </button>
      <button type="button" class="btn-admin btn-admin--peligro" data-accion="archivar">
        <i class="fa-solid fa-box-archive"></i> Archivar
      </button>
    <?php endif; ?>
    <?php if (!$enBd && $codigo !== ''): ?>
      <button type="button" class="btn-admin btn-admin--aviso" data-accion="sincronizar">
        <i class="fa-solid fa-arrows-rotate"></i> Sincronizar con la BD
      </button>
    <?php endif; ?>
  </div>
</div>
