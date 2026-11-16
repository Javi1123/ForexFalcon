<?php
/* Cabecera de cada sección de administración: banner + pestañas.
   Variables: $heroIcono, $heroTitulo, $heroTexto, $heroDatos (opcional: [[valor, etiqueta], ...]), $adminSeccion. */
require_once __DIR__ . '/helpers.php';
$pestanas = [
  'panel'     => ['/admin',           'fa-solid fa-gauge-high',        'Panel'],
  'servicios' => ['/admin/servicios', 'fa-solid fa-sliders',           'Servicios'],
  'usuarios'  => ['/admin/usuarios',  'fa-solid fa-users-gear',        'Usuarios'],
  'actividad' => ['/admin/actividad', 'fa-solid fa-clock-rotate-left', 'Actividad'],
];
?>
<nav class="adm-pestanas" aria-label="Secciones de administración">
  <?php foreach ($pestanas as $clave => $p): ?>
    <a href="<?= BASE_PATH . $p[0] ?>" class="adm-pestana<?= ($adminSeccion ?? '') === $clave ? ' activa' : '' ?>">
      <i class="<?= $p[1] ?>"></i> <?= $p[2] ?>
    </a>
  <?php endforeach; ?>
</nav>

<?php if (!empty($heroTitulo)): ?>
<section class="adm-hero" data-reveal>
  <div class="adm-hero__texto">
    <h3><i class="<?= adm_e($heroIcono ?? 'fa-solid fa-shield-halved') ?>"></i> <?= adm_e($heroTitulo) ?></h3>
    <?php if (!empty($heroTexto)): ?><p><?= adm_e($heroTexto) ?></p><?php endif; ?>
  </div>
  <?php if (!empty($heroDatos)): ?>
    <ul class="adm-hero__datos">
      <?php foreach ($heroDatos as $d): ?>
        <li><strong data-contar><?= adm_e($d[0]) ?></strong><span><?= adm_e($d[1]) ?></span></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</section>
<?php endif; ?>
