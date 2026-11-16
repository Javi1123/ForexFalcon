<?php
require_once __DIR__ . '/helpers.php';

$heroIcono  = 'fa-solid fa-clock-rotate-left';
$heroTitulo = 'Actividad de usuarios';
$heroTexto  = 'Registro de inicios y cierres de sesión: quién entró, cuándo, desde qué dispositivo y qué dirección IP.';
$heroDatos  = [[$enLinea, 'con sesión abierta'], [$lista['total'], 'eventos en el registro']];

$hayFiltros = $filtros['q'] !== '' || $filtros['tipo'] !== '' || $filtros['desde'] !== '' || $filtros['hasta'] !== '';
$desde = $lista['total'] ? (($pagina - 1) * $porPagina) + 1 : 0;
$hasta = min($lista['total'], $pagina * $porPagina);

$max = 1;
foreach ($serie as $d) { $max = max($max, $d['n']); }
$dias = ['Mon' => 'L', 'Tue' => 'M', 'Wed' => 'X', 'Thu' => 'J', 'Fri' => 'V', 'Sat' => 'S', 'Sun' => 'D'];
$hoy = date('Y-m-d');
?>
<main class="app-main adm">
  <div class="app-content-header">
    <div class="container-fluid">
      <?php include __DIR__ . '/cabecera_seccion.php'; ?>
    </div>
  </div>

  <div class="app-content">
    <div class="container-fluid">

      <?php if ($errorDatos): ?>
        <div class="alert alert-danger admin-alerta" role="alert">
          <i class="fa-solid fa-circle-exclamation me-2"></i> No se pudo leer el registro de actividad de la base de datos.
        </div>
      <?php endif; ?>

      <!-- Resumen del filtro actual + gráfico -->
      <div class="adm-dos adm-dos--actividad">
        <div class="adm-minikpis">
          <div class="adm-kpi adm-kpi--fijo" data-reveal style="--i: 0"><span class="adm-kpi__icono"><i class="fa-solid fa-right-to-bracket"></i></span><span class="adm-kpi__valor" data-contar><?= $resumenFiltro['logins'] ?></span><span class="adm-kpi__texto">Inicios de sesión<?= $hayFiltros ? ' (filtro)' : '' ?></span></div>
          <div class="adm-kpi adm-kpi--fijo" data-reveal style="--i: 1"><span class="adm-kpi__icono"><i class="fa-solid fa-right-from-bracket"></i></span><span class="adm-kpi__valor" data-contar><?= $resumenFiltro['logouts'] ?></span><span class="adm-kpi__texto">Cierres de sesión<?= $hayFiltros ? ' (filtro)' : '' ?></span></div>
          <div class="adm-kpi adm-kpi--fijo" data-reveal style="--i: 2"><span class="adm-kpi__icono"><i class="fa-solid fa-user-group"></i></span><span class="adm-kpi__valor" data-contar><?= $resumenFiltro['usuarios'] ?></span><span class="adm-kpi__texto">Usuarios distintos<?= $hayFiltros ? ' (filtro)' : '' ?></span></div>
        </div>

        <section class="adm-tarjeta" data-reveal style="--i: 2">
          <header class="adm-tarjeta__cab">
            <h4><i class="fa-solid fa-chart-column"></i> Inicios de sesión · 14 días</h4>
          </header>
          <div class="adm-barras adm-barras--baja" role="img" aria-label="Inicios de sesión por día en los últimos 14 días">
            <?php foreach ($serie as $d): $h = $d['n'] > 0 ? max(6, round($d['n'] / $max * 100)) : 2; $t = strtotime($d['dia']); ?>
              <a class="adm-barra" href="<?= adm_e(adm_url('/admin/actividad', ['tipo' => 'login'], ['desde' => $d['dia'], 'hasta' => $d['dia']])) ?>" title="<?= date('d/m/Y', $t) ?>: <?= (int) $d['n'] ?> inicio<?= $d['n'] === 1 ? '' : 's' ?> (pulsa para filtrar ese día)">
                <span class="adm-barra__n"><?= (int) $d['n'] ?></span>
                <span class="adm-barra__col<?= $d['n'] === 0 ? ' vacia' : '' ?>" style="--h: <?= $h ?>"></span>
                <span class="adm-barra__dia"><?= date('j', $t) ?><small><?= $dias[date('D', $t)] ?></small></span>
              </a>
            <?php endforeach; ?>
          </div>
        </section>
      </div>

      <!-- Filtros -->
      <form class="adm-filtros adm-filtros--actividad" method="get" action="<?= BASE_PATH ?>/admin/actividad" data-reveal style="--i: 1">
        <label class="adm-buscar">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="search" name="q" value="<?= adm_e($filtros['q']) ?>" maxlength="80" placeholder="Buscar por nombre, correo o IP" aria-label="Buscar en la actividad">
        </label>
        <select name="tipo" aria-label="Tipo de evento" data-autoenviar>
          <option value="">Todos los eventos</option>
          <option value="login"<?= $filtros['tipo'] === 'login' ? ' selected' : '' ?>>Inicios de sesión</option>
          <option value="logout"<?= $filtros['tipo'] === 'logout' ? ' selected' : '' ?>>Cierres de sesión</option>
        </select>
        <label class="adm-fecha">Desde <input type="date" name="desde" value="<?= adm_e($filtros['desde']) ?>" max="<?= $hoy ?>" data-autoenviar></label>
        <label class="adm-fecha">Hasta <input type="date" name="hasta" value="<?= adm_e($filtros['hasta']) ?>" max="<?= $hoy ?>" data-autoenviar></label>
        <button type="submit" class="btnRegistrarse"><i class="fa-solid fa-filter"></i> Filtrar</button>
        <?php if ($hayFiltros): ?><a class="adm-limpiar" href="<?= BASE_PATH ?>/admin/actividad"><i class="fa-solid fa-xmark"></i> Limpiar</a><?php endif; ?>
        <a class="adm-btn adm-btn--suave adm-exportar" href="<?= adm_e(adm_url('/admin/actividad/exportar', $filtros)) ?>"><i class="fa-solid fa-file-csv"></i> Exportar CSV</a>
      </form>

      <p class="adm-contador" data-reveal style="--i: 2">
        <?= $lista['total'] ? "Mostrando <strong>{$desde}–{$hasta}</strong> de <strong>{$lista['total']}</strong> evento" . ($lista['total'] === 1 ? '' : 's') : 'Sin resultados' ?>
      </p>

      <?php if (!$lista['filas']): ?>
        <div class="adm-vacio" data-reveal>
          <i class="fa-regular fa-calendar-xmark"></i>
          <h4>No hay actividad que coincida</h4>
          <p>Prueba con otro rango de fechas o quita algún filtro.</p>
        </div>
      <?php else: ?>
      <div class="adm-tabla-wrap" data-reveal style="--i: 3">
        <table class="adm-tabla">
          <thead><tr><th>Fecha</th><th>Usuario</th><th>Evento</th><th>Dispositivo</th><th>IP</th></tr></thead>
          <tbody>
            <?php foreach ($lista['filas'] as $i => $r):
              $in = $r['Tipo_evento'] === 'login';
              $nombre = trim(($r['Nombre'] ?? '') . ' ' . ($r['Apellido'] ?? '')); ?>
              <tr style="--r: <?= $i ?>">
                <td data-label="Fecha" title="<?= adm_e(adm_hace($r['Fecha_evento'])) ?>"><?= adm_e(adm_fecha($r['Fecha_evento'])) ?></td>
                <td data-label="Usuario">
                  <div class="adm-persona">
                    <span class="adm-avatar" style="background: <?= adm_e(adm_color((string) ($r['Nombre'] ?? ''), (string) ($r['Apellido'] ?? ''))) ?>"><?= adm_e(adm_inicial((string) ($r['Nombre'] ?? ''), $r['Correo'])) ?></span>
                    <span class="adm-persona__texto">
                      <strong><?= adm_e($nombre ?: $r['Correo']) ?></strong>
                      <small><a href="<?= adm_e(adm_url('/admin/actividad', [], ['q' => $r['Correo']])) ?>" title="Ver solo la actividad de este usuario"><?= adm_e($r['Correo']) ?></a></small>
                    </span>
                  </div>
                </td>
                <td data-label="Evento"><span class="adm-tag-evento adm-tag-evento--<?= $in ? 'in' : 'out' ?>"><i class="fa-solid <?= $in ? 'fa-right-to-bracket' : 'fa-right-from-bracket' ?>"></i> <?= $in ? 'Inicio de sesión' : 'Cierre de sesión' ?></span></td>
                <td data-label="Dispositivo" title="<?= adm_e($r['User_agent']) ?>"><?= adm_e($r['Dispositivo']) ?></td>
                <td data-label="IP" class="mono"><?= adm_e($r['Ip_address'] ?: '—') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <?php $ruta = '/admin/actividad'; include __DIR__ . '/paginacion.php'; ?>
      <?php endif; ?>
    </div>
  </div>
</main>
