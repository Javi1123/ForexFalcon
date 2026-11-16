<?php
require_once __DIR__ . '/helpers.php';

$heroIcono  = 'fa-solid fa-gauge-high';
$heroTitulo = 'Panel de administración';
$heroTexto  = 'Un vistazo rápido a tus usuarios, sus accesos y los servicios. Desde aquí puedes ir a cada sección.';
$heroDatos  = $resumen ? [[$resumen['usuarios'], 'usuarios'], [$resumen['en_linea'], 'con sesión abierta']] : [];

$kpis = $resumen ? [
  ['fa-solid fa-users',          'Usuarios registrados',   $resumen['usuarios'],        '/admin/usuarios'],
  ['fa-solid fa-user-plus',      'Nuevos (últimos 7 días)', $resumen['nuevos7'],         '/admin/usuarios?orden=recientes'],
  ['fa-solid fa-circle-dot',     'Con sesión abierta',     $resumen['en_linea'],        '/admin/usuarios?estado=en_linea'],
  ['fa-solid fa-user-shield',    'Administradores',        $resumen['administradores'], '/admin/usuarios?rol=Administrador'],
  ['fa-solid fa-box-open',       'Servicios activos',      $resumen['servicios'],       '/admin/servicios'],
  ['fa-solid fa-id-card-clip',   'Suscripciones vigentes', $resumen['suscripciones'],   '/admin/usuarios'],
] : [];

$max = 1;
foreach ($serie ?? [] as $d) { $max = max($max, $d['n']); }
$dias = ['Mon' => 'L', 'Tue' => 'M', 'Wed' => 'X', 'Thu' => 'J', 'Fri' => 'V', 'Sat' => 'S', 'Sun' => 'D'];
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
          <i class="fa-solid fa-circle-exclamation me-2"></i> No se pudieron leer los datos de la base de datos. Revisa la conexión y vuelve a intentarlo.
        </div>
      <?php else: ?>

      <!-- Indicadores -->
      <div class="adm-kpis">
        <?php foreach ($kpis as $i => $k): ?>
          <a href="<?= BASE_PATH . $k[3] ?>" class="adm-kpi" data-reveal style="--i: <?= $i ?>">
            <span class="adm-kpi__icono"><i class="<?= $k[0] ?>"></i></span>
            <span class="adm-kpi__valor" data-contar><?= (int) $k[2] ?></span>
            <span class="adm-kpi__texto"><?= adm_e($k[1]) ?></span>
          </a>
        <?php endforeach; ?>
      </div>

      <div class="adm-dos">
        <!-- Gráfico de inicios de sesión -->
        <section class="adm-tarjeta" data-reveal style="--i: 1">
          <header class="adm-tarjeta__cab">
            <h4><i class="fa-solid fa-chart-column"></i> Inicios de sesión · últimos 14 días</h4>
            <span class="adm-pildora"><?= (int) $resumen['logins_hoy'] ?> hoy</span>
          </header>
          <div class="adm-barras" role="img" aria-label="Inicios de sesión por día en los últimos 14 días">
            <?php foreach ($serie as $d): $h = $d['n'] > 0 ? max(6, round($d['n'] / $max * 100)) : 2; $t = strtotime($d['dia']); ?>
              <div class="adm-barra" title="<?= date('d/m/Y', $t) ?>: <?= (int) $d['n'] ?> inicio<?= $d['n'] === 1 ? '' : 's' ?>">
                <span class="adm-barra__n"><?= (int) $d['n'] ?></span>
                <span class="adm-barra__col<?= $d['n'] === 0 ? ' vacia' : '' ?>" style="--h: <?= $h ?>"></span>
                <span class="adm-barra__dia"><?= date('j', $t) ?><small><?= $dias[date('D', $t)] ?></small></span>
              </div>
            <?php endforeach; ?>
          </div>
        </section>

        <!-- Últimos registros -->
        <section class="adm-tarjeta" data-reveal style="--i: 2">
          <header class="adm-tarjeta__cab">
            <h4><i class="fa-solid fa-user-plus"></i> Últimos registros</h4>
            <a href="<?= BASE_PATH ?>/admin/usuarios" class="adm-enlace">Ver todos →</a>
          </header>
          <?php if (!$registrados): ?>
            <p class="adm-vacio-mini">Todavía no hay usuarios.</p>
          <?php else: ?>
            <ul class="adm-lista">
              <?php foreach ($registrados as $u): ?>
                <li>
                  <span class="adm-avatar" style="background: <?= adm_e(adm_color($u['Nombre'], $u['Apellido'])) ?>"><?= adm_e(adm_inicial($u['Nombre'], $u['Correo'])) ?></span>
                  <span class="adm-lista__texto">
                    <strong><?= adm_e(trim($u['Nombre'] . ' ' . $u['Apellido'])) ?></strong>
                    <small><?= adm_e($u['Correo']) ?></small>
                  </span>
                  <span class="adm-lista__fecha"><?= adm_e(adm_hace($u['Fecha_creacion'])) ?></span>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </section>
      </div>

      <!-- Actividad reciente -->
      <section class="adm-tarjeta" data-reveal style="--i: 1">
        <header class="adm-tarjeta__cab">
          <h4><i class="fa-solid fa-clock-rotate-left"></i> Actividad reciente</h4>
          <a href="<?= BASE_PATH ?>/admin/actividad" class="adm-enlace">Ver todo el registro →</a>
        </header>
        <?php if (!$actividadReciente): ?>
          <p class="adm-vacio-mini">Todavía no hay accesos registrados.</p>
        <?php else: ?>
          <ul class="adm-lista adm-lista--eventos">
            <?php foreach ($actividadReciente as $r): $in = $r['Tipo_evento'] === 'login'; ?>
              <li>
                <span class="adm-evento adm-evento--<?= $in ? 'in' : 'out' ?>"><i class="fa-solid <?= $in ? 'fa-right-to-bracket' : 'fa-right-from-bracket' ?>"></i></span>
                <span class="adm-lista__texto">
                  <strong><?= adm_e(trim(($r['Nombre'] ?? '') . ' ' . ($r['Apellido'] ?? '')) ?: $r['Correo']) ?></strong>
                  <small><?= $in ? 'Inició sesión' : 'Cerró sesión' ?> · <?= adm_e($r['Dispositivo']) ?></small>
                </span>
                <span class="adm-lista__fecha" title="<?= adm_e(adm_fecha($r['Fecha_evento'])) ?>"><?= adm_e(adm_hace($r['Fecha_evento'])) ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </section>

      <?php endif; ?>
    </div>
  </div>
</main>
