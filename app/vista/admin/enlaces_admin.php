<?php
/* Enlaces de administración de la barra lateral (los incluyen menu_admin.php y recursos/menu.php).
   $adminSeccion: 'panel' | 'usuarios' | 'actividad' | 'servicios' (si no viene definida, $vistaAdmin cuenta como 'servicios'). */
$seccionAdmin = $adminSeccion ?? (!empty($vistaAdmin) ? 'servicios' : '');
$enlacesAdmin = [
  ['panel',     '/admin',           'fa-solid fa-gauge-high',      'Panel',                 'var(--verde)'],
  ['servicios', '/admin/servicios', 'fa-solid fa-sliders',         'Administrar servicios', 'var(--naranja)'],
  ['usuarios',  '/admin/usuarios',  'fa-solid fa-users-gear',      'Usuarios',              'var(--verde)'],
  ['actividad', '/admin/actividad', 'fa-solid fa-clock-rotate-left', 'Actividad',           'var(--naranja)'],
];
?>
<li class="nav-item" style="padding: 4px 0 8px;">
  <span class="nav-section-title" style="display: block; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: rgba(255,255,255,0.2); padding: 0 16px;">Administración</span>
</li>
<?php foreach ($enlacesAdmin as $e): ?>
<li class="nav-item">
  <a href="<?= BASE_PATH . $e[1] ?>" class="nav-link <?= $seccionAdmin === $e[0] ? 'active' : '' ?>" style="border-radius: 10px; padding: 10px 16px; color: rgba(255,255,255,0.7); transition: all 0.3s ease; display: flex; align-items: center; gap: 12px;">
    <i class="<?= $e[2] ?>" style="color: <?= $e[4] ?>; font-size: 1rem; width: 20px; text-align: center;"></i>
    <p style="margin: 0; font-weight: 500; font-size: 0.9rem; flex: 1;"><?= $e[3] ?></p>
  </a>
</li>
<?php endforeach; ?>
