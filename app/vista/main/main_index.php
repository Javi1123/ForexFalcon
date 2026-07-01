<?php /* min/main_index.php — Rediseño ForexFalcon */ ?>

<main>

  <!-- ═══════════════════════════════════════════
       ESENCIA
  ═══════════════════════════════════════════ -->
  <section id="esencia" class="container-fluid py-0">
    <div class="container py-5" style="padding-top:80px !important; padding-bottom:80px !important;">

      <!-- Cabecera -->
      <div class="reveal reveal-up">
        <p class="section-eyebrow">Por qué elegirnos</p>
        <h2 class="section-heading">Nuestra <span>esencia</span></h2>
        <p class="section-sub">Más que una academia — somos tu socio en el camino hacia la libertad financiera.</p>
      </div>

      <!-- Grid de features -->
      <div class="features-grid">

        <div class="feature-card reveal reveal-up delay-1-cards">
          <div class="feature-icon-wrap">
            <i class="fas fa-user-check"></i>
          </div>
          <h3>Mentoría 1:1 real</h3>
          <p>Mentor exclusivo que revisa tus operaciones en vivo y responde en minutos. Sin bots ni respuestas genéricas.</p>
        </div>

        <div class="feature-card reveal reveal-up delay-2-cards">
          <div class="feature-icon-wrap">
            <i class="fas fa-chalkboard-teacher"></i>
          </div>
          <h3>Aprende con operaciones en vivo</h3>
          <p>Cientos de casos reales en grupos privados y webinars mensuales con aciertos, errores y correcciones en directo.</p>
        </div>

        <div class="feature-card reveal reveal-up delay-3-cards">
          <div class="feature-icon-wrap">
            <i class="fas fa-robot"></i>
          </div>
          <h3>Bots automáticos + copy trading</h3>
          <p>Algoritmos configurables y copy trading selectivo. Opera mientras duermes, con control total del riesgo.</p>
        </div>

        <div class="feature-card reveal reveal-up delay-4-cards">
          <div class="feature-icon-wrap">
            <i class="fas fa-users"></i>
          </div>
          <h3>Comunidad colaborativa activa</h3>
          <p>Red de traders activos que comparten alertas, ideas y estrategias. Aprende de todos, no solo del mentor.</p>
        </div>

        <div class="feature-card reveal reveal-up delay-5-cards">
          <div class="feature-icon-wrap">
            <i class="fas fa-chart-line"></i>
          </div>
          <h3>Análisis semanal ejecutivo</h3>
          <p>Cada lunes, informe claro con pares clave, niveles y escenarios probables. Olvida perder horas buscando noticias.</p>
        </div>

        <div class="feature-card reveal reveal-up delay-6-cards">
          <div class="feature-icon-wrap">
            <i class="fas fa-mountain"></i>
          </div>
          <h3>Resultados sostenibles, paso a paso</h3>
          <p>Fase 1: control del riesgo. Fase 2: consistencia. Fase 3: ingresos complementarios. Libertad financiera real.</p>
        </div>

      </div>
    </div>
  </section>


  <!-- ═══════════════════════════════════════════
       CATÁLOGO
  ═══════════════════════════════════════════ -->
  <section id="catalogo" class="container-fluid">
    <div class="container" style="padding-top:80px; padding-bottom:80px;">

      <!-- Cabecera -->
      <div class="reveal reveal-up">
        <p class="section-eyebrow" style="color:var(--verde)">Nuestros servicios</p>
        <h2 class="section-heading" style="color:#fff">Catálogo</h2>
        <p class="section-sub" style="color:var(--blanco-dim)">Elige el plan que encaja con tu momento y tus objetivos.</p>
      </div>

      <!-- Grid de planes -->
      <div class="plans-grid">

        <!-- CopyTrading -->
        <div class="plan-card reveal reveal-down delay-1">
          <span class="plan-badge green">Totalmente gratis</span>
          <div class="plan-icon">
            <i class="fas fa-copy"></i>
          </div>
          <h3>CopyTrading</h3>
          <div class="plan-price">€0</div>
          <p class="plan-desc">Copia operaciones reales en tiempo real. Sin experiencia previa ni estrés.</p>
          <ul class="plan-features">
            <li>Depósito mínimo $100</li>
            <li>Activo en 5 minutos</li>
            <li>Sin comisiones adicionales</li>
          </ul>
          <?php if(isset($_SESSION['usuario'])) : ?>
            <a class="plan-btn primary" href="<?= BASE_PATH . "/copytrading" ?>">Activar ahora →</a>
          <?php else : ?>
            <a class="plan-btn primary" href="<?= BASE_PATH . "/acciones?tipo=registro" ?>">Activar cuenta →</a>
          <?php endif; ?>
        </div>

        <!-- Mentorías -->
        <div class="plan-card featured reveal reveal-down delay-2">
          <span class="plan-badge popular">⭐ Más popular</span>
          <div class="plan-icon orange">
            <i class="fas fa-user-graduate"></i>
          </div>
          <h3>Mentorías 1:1</h3>
          <p class="plan-desc">Aprende la estrategia 100% funcional con acompañamiento personalizado.</p>
          <div class="mentoria-precios">
            <div class="mentoria-row">
              <span class="label">1 mes</span>
              <span class="precio">€150</span>
            </div>
            <div class="mentoria-row">
              <span class="label">3 meses</span>
              <span class="precio">€350</span>
            </div>
            <div class="mentoria-row">
              <span class="label">1 año</span>
              <span class="precio">€999</span>
            </div>
          </div>
          <?php if(isset($_SESSION['usuario'])) : ?>
            <a class="plan-btn primary" href="<?= BASE_PATH . "/mentorias" ?>">Ver planes →</a>
          <?php else : ?>
            <a class="plan-btn primary" href="<?= BASE_PATH . "/acciones?tipo=registro" ?>">Activar cuenta →</a>
          <?php endif; ?>
        </div>

        <!-- Bots -->
        <div class="plan-card reveal reveal-down delay-3">
          <span class="plan-badge orange">€75 / mes</span>
          <div class="plan-icon orange">
            <i class="fas fa-microchip"></i>
          </div>
          <h3>Bots de trading</h3>
          <p class="plan-desc">Algoritmos que operan por ti 24/7 de lunes a viernes basados en nuestra estrategia.</p>
          <ul class="plan-features">
            <li>Opera sin descanso</li>
            <li>Basado en estrategia probada</li>
            <li>Resultados consistentes</li>
          </ul>
          <?php if(isset($_SESSION['usuario'])) : ?>
            <a class="plan-btn" href="<?= BASE_PATH . "/bots" ?>">Activar bot →</a>
          <?php else : ?>
            <a class="plan-btn" href="<?= BASE_PATH . "/acciones?tipo=registro" ?>">Activar cuenta →</a>
          <?php endif; ?>
        </div>

        <!-- Análisis Premium -->
        <div class="plan-card reveal reveal-down delay-4">
          <span class="plan-badge orange">€50 / mes</span>
          <div class="plan-icon">
            <i class="fas fa-gem"></i>
          </div>
          <h3>Análisis Premium</h3>
          <p class="plan-desc">Predicciones diarias sobre materias primas y divisas en un grupo exclusivo.</p>
          <ul class="plan-features">
            <li>Señales claras de entrada/salida</li>
            <li>Análisis fundamental y técnico</li>
            <li>Soporte directo en el grupo</li>
          </ul>
          <?php if(isset($_SESSION['usuario'])) : ?>
            <a class="plan-btn" href="<?= BASE_PATH . "/analisis" ?>">Suscribirse →</a>
          <?php else : ?>
            <a class="plan-btn" href="<?= BASE_PATH . "/acciones?tipo=registro" ?>">Activar cuenta →</a>
          <?php endif; ?>
        </div>

      </div>
    </div>
  </section>


  <!-- ═══════════════════════════════════════════
       FAQs
  ═══════════════════════════════════════════ -->
  <section id="FAQs" class="container-fluid">
    <div class="container" style="padding-top:80px; padding-bottom:80px;">

      <div class="reveal reveal-up mb-5">
        <p class="section-eyebrow" style="color:var(--verde)">Dudas frecuentes</p>
        <h2 class="section-heading" style="color:#fff">FAQ<span>s</span></h2>
        <p class="section-sub" style="color:var(--blanco-dim)">Todo lo que necesitas saber antes de empezar.</p>
      </div>

      <div class="accordion reveal reveal-scale" id="accordion">

        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true">
              ¿Necesito experiencia previa para empezar?
            </button>
          </h2>
          <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#accordion">
            <div class="accordion-body">
              No. Tenemos rutas de aprendizaje diseñadas desde cero. El CopyTrading te permite generar resultados desde el primer día mientras vas aprendiendo la estrategia a tu ritmo.
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
              ¿Cuánto dinero necesito para empezar?
            </button>
          </h2>
          <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#accordion">
            <div class="accordion-body">
              El CopyTrading solo requiere un depósito mínimo de $100 en tu bróker. El precio de las mentorías y suscripciones es independiente del capital que decidas operar.
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
              ¿Los bots son seguros? ¿Puedo perder todo mi capital?
            </button>
          </h2>
          <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#accordion">
            <div class="accordion-body">
              Los bots incluyen gestión de riesgo integrada. Tú controlas el porcentaje máximo de exposición por operación y puedes detenerlos en cualquier momento. El trading siempre conlleva riesgo: nunca inviertas dinero que no puedas permitirte perder.
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
              ¿Cuál es el costo de los planes y hay descuentos?
            </button>
          </h2>
          <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#accordion">
            <div class="accordion-body">
              El CopyTrading es totalmente gratuito. Las mentorías van desde €150/mes hasta €999 al año (el más económico por mes). Los bots cuestan €75/mes y el análisis premium €50/mes. Para más información escríbenos a <strong style="color:var(--naranja)">info@forexfalcon.com</strong>.
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
              ¿Qué diferencia ForexFalcon de otras academias?
            </button>
          </h2>
          <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#accordion">
            <div class="accordion-body">
              Combinamos formación real, bots automáticos, análisis premium y mentoría 1:1 en un solo ecosistema. No vendemos promesas vacías ni atajos — enseñamos a operar con método, disciplina y resultados sostenibles.
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
              ¿Cómo es el proceso de inscripción?
            </button>
          </h2>
          <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#accordion">
            <div class="accordion-body">
              Crea tu cuenta gratuita en minutos, elige el plan que mejor encaje con tus objetivos y empieza. Si tienes dudas, contáctanos directamente y te guiamos sin compromiso.
              <div class="mt-3">
                <a href="<?= BASE_PATH . "/acciones?tipo=registro" ?>" class="btnRegistrarse">Registrarse gratis →</a>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>


  <!-- ═══════════════════════════════════════════
       CTA FINAL
  ═══════════════════════════════════════════ -->
  <section id="registroFinal-section" class="container-fluid">
    <div class="cta-ring cta-ring-1"></div>
    <div class="cta-ring cta-ring-2"></div>
    <div class="cta-ring cta-ring-3"></div>
    <div class="container text-center" style="position:relative; z-index:2; padding-top:100px; padding-bottom:100px;">

      <?php if(isset($_SESSION['usuario'])) : ?>
        <h2 class="reveal reveal-up">
          Estás cambiando tu <span class="green">vida financiera</span>
        </h2>
        <p class="reveal reveal-up" style="color:var(--blanco-dim); font-size:1.1rem; margin-top:14px;">
          Ya estás en ForexFalcon. Ahora descubre cómo el trading se convierte en tu camino real hacia la libertad.
        </p>
        <a href="<?= BASE_PATH . "/recursos" ?>" class="hero__cta reveal reveal-up" style="margin-top:32px;">
          <i class="fa-solid fa-play"></i>
          Ir a mis recursos
        </a>
      <?php else : ?>
        <h2 class="reveal reveal-up">
          ¿Listo para cambiar tu <span class="green">vida financiera</span>?
        </h2>
        <p class="reveal reveal-up" style="color:var(--blanco-dim); font-size:1.1rem; margin-top:14px;">
          Únete hoy a ForexFalcon y descubre cómo el trading puede convertirse en tu camino hacia la libertad.
        </p>
        <a href="<?= BASE_PATH . "/acciones?tipo=registro" ?>" class="hero__cta reveal reveal-up" style="margin-top:32px;">
          <i class="fas fa-rocket"></i>
          Comienza tu viaje
        </a>
      <?php endif; ?>

    </div>
  </section>

</main>