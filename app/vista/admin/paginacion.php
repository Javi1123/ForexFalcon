<?php
/* Paginación. Variables: $ruta, $filtros (los de la búsqueda), $pagina, $totalPaginas. */
if ($totalPaginas <= 1) return;

$ventana = [];
for ($i = 1; $i <= $totalPaginas; $i++) {
  if ($i === 1 || $i === $totalPaginas || abs($i - $pagina) <= 1) { $ventana[] = $i; }
}
?>
<nav class="adm-paginacion" aria-label="Paginación">
  <a class="adm-pag<?= $pagina <= 1 ? ' off' : '' ?>" href="<?= $pagina <= 1 ? '#' : adm_e(adm_url($ruta, $filtros, ['pagina' => $pagina - 1])) ?>" aria-label="Anterior"><i class="fa-solid fa-chevron-left"></i></a>
  <?php $prev = 0; foreach ($ventana as $n): ?>
    <?php if ($n - $prev > 1): ?><span class="adm-pag-puntos">…</span><?php endif; ?>
    <a class="adm-pag<?= $n === $pagina ? ' activa' : '' ?>" href="<?= adm_e(adm_url($ruta, $filtros, ['pagina' => $n])) ?>"><?= $n ?></a>
  <?php $prev = $n; endforeach; ?>
  <a class="adm-pag<?= $pagina >= $totalPaginas ? ' off' : '' ?>" href="<?= $pagina >= $totalPaginas ? '#' : adm_e(adm_url($ruta, $filtros, ['pagina' => $pagina + 1])) ?>" aria-label="Siguiente"><i class="fa-solid fa-chevron-right"></i></a>
</nav>
