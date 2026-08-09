<?php
/*
  Datos de los servicios. Ajusta nombre, precio, descripción corta (card),
  descripción larga, características y URL de pago según corresponda.
*/
$servicios = [
  [
    'slug'          => 'copytrading',
    'icono'         => 'fa-solid fa-chart-line',
    'nombre'        => 'CopyTrading',
    'precio'        => '49€',
    'periodo'       => '/mes',
    'descripcion_corta' => 'Copia automáticamente las operaciones de nuestros traders profesionales en tu propia cuenta.',
    'descripcion_larga' => 'Con CopyTrading conectas tu cuenta a las operativas de traders profesionales verificados por ForexFalcon. Cada operación que ellos abren se replica automáticamente en tu cuenta, respetando el tamaño de posición que definas.',
    'caracteristicas' => [
      'Replicación automática en tiempo real',
      'Gestión de riesgo configurable por operación',
      'Panel de seguimiento de rendimiento',
      'Cancela cuando quieras, sin permanencia',
    ],
  ],
  [
    'slug'          => 'mentorias',
    'icono'         => 'fa-solid fa-user-graduate',
    'nombre'        => 'Mentorías 1:1',
    'precio'        => '150€',
    'periodo'       => '/sesión',
    'descripcion_corta' => 'Sesiones personalizadas con un trader profesional adaptadas a tu nivel y objetivos.',
    'descripcion_larga' => 'Sesión individual de una hora con un mentor de ForexFalcon. Revisamos tu operativa, tu psicotrading y tu gestión de riesgo, y diseñamos un plan de mejora concreto para tus próximas semanas.',
    'caracteristicas' => [
      'Sesión 1:1 de 60 minutos por videollamada',
      'Análisis de tu histórico de operaciones',
      'Plan de acción personalizado',
      'Material de apoyo tras la sesión',
    ],
  ],
  [
    'slug'          => 'bots',
    'icono'         => 'fa-solid fa-robot',
    'nombre'        => 'Bots de Trading',
    'precio'        => '199€',
    'periodo'       => 'pago único',
    'descripcion_corta' => 'Algoritmos automatizados que operan por ti 24/5 según la estrategia que elijas.',
    'descripcion_larga' => 'Nuestros bots ejecutan estrategias probadas de forma automática, sin necesidad de estar pendiente del mercado. Incluyen instalación, configuración inicial y actualizaciones durante el primer año.',
    'caracteristicas' => [
      'Instalación y configuración incluidas',
      'Estrategias probadas y auditadas',
      'Actualizaciones durante 12 meses',
      'Compatible con las principales plataformas',
    ],
  ],
  [
    'slug'          => 'analisis-premium',
    'icono'         => 'fa-solid fa-chart-pie',
    'nombre'        => 'Análisis Premium',
    'precio'        => '29€',
    'periodo'       => '/mes',
    'descripcion_corta' => 'Señales y análisis de mercado diario elaborados por nuestro equipo de analistas.',
    'descripcion_larga' => 'Recibe cada día análisis técnico y fundamental de los principales pares de divisas, junto con señales de entrada y salida, directamente en tu panel y por notificación.',
    'caracteristicas' => [
      'Análisis diario de mercado',
      'Señales de entrada y salida',
      'Notificaciones en tiempo real',
      'Histórico de análisis anteriores',
    ],
  ],
];
?>
<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h3 class="mb-0">Tienda</h3>
        </div>
      </div>
    </div>
  </div>
  <div class="app-content">
    <div class="container-fluid">

      <div class="servicios-grid">
        <?php foreach ($servicios as $s): ?>
          <div class="servicio-card">
            <div class="servicio-card__icon">
              <i class="<?= $s['icono'] ?>"></i>
            </div>
            <h3><?= htmlspecialchars($s['nombre']) ?></h3>
            <p class="servicio-card__desc"><?= htmlspecialchars($s['descripcion_corta']) ?></p>
            <div class="servicio-card__precio">
              <?= htmlspecialchars($s['precio']) ?>
              <span class="periodo"><?= htmlspecialchars($s['periodo']) ?></span>
            </div>
            <div class="servicio-card__acciones">
              <a href="<?= BASE_PATH . "/pago?servicio=" . $s['slug'] ?>" class="btnRegistrarse">
                Proceder con el pago
              </a>
              <button
                type="button"
                class="btn-info-servicio"
                data-titulo="<?= htmlspecialchars($s['nombre']) ?>"
                data-descripcion="<?= htmlspecialchars($s['descripcion_larga']) ?>"
                data-precio="<?= htmlspecialchars($s['precio'] . ' ' . $s['periodo']) ?>"
                data-caracteristicas='<?= htmlspecialchars(json_encode($s['caracteristicas'], JSON_UNESCAPED_UNICODE)) ?>'
                data-url-pago="<?= BASE_PATH . "/pago?servicio=" . $s['slug'] ?>"
                aria-label="Información de <?= htmlspecialchars($s['nombre']) ?>"
                title="Más información"
              >
                <i class="fa-solid fa-circle-info"></i>
              </button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</main>

<!-- Modal de información del servicio (uno solo, reutilizado por las 4 cards) -->
<div class="modal fade" id="modalServicioInfo" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalServicioTitulo"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p id="modalServicioDescripcion"></p>
        <ul id="modalServicioCaracteristicas" class="servicio-caracteristicas"></ul>
        <div class="modal-precio-final" id="modalServicioPrecio"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
        <a href="#" id="modalServicioBtnPagar" class="btnRegistrarse">Proceder con el pago</a>
      </div>
    </div>
  </div>
</div>


