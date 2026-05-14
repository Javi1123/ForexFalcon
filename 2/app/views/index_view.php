<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="icon" type="image/x-icon" href="<?= LINKS_PATH . "/imagenes/favicon.ico" ?> ">

  <!-- ForexFalcon Stylesheet -->
  <link rel="stylesheet" href="<?= LINKS_PATH . "/css/style.css" ?> ">
  <script src="<?= LINKS_PATH . "/js/forexfalcon.js" ?> " defer></script>

  <!-- Tailwind -->
  <script src="<?= LINKS_PATH . "/js/tailwind.js" ?> "></script>

  <!-- DaisyUI -->
  <link href="<?= LINKS_PATH . "/css/daisyUI.css" ?> " rel="stylesheet" type="text/css" />
  <script src="<?= LINKS_PATH . "/js/daisyUI.js" ?> "></script>

  <!-- Font Awesome -->
  <script src="<?= LINKS_PATH . "/js/all.min.js" ?> "></script>

  <!-- Google font -->
  <link rel="stylesheet" href="<?= LINKS_PATH . "/css/style.css" ?> ">

  <title>ForexFalcon | Academia de Trading con Asistencia 1:1</title>
</head>
<body class="bg-gray-100 font-sans antialiased">

  <!-- ===== HEADER (SIN MODIFICACIONES) ===== -->
  <header class="sticky top-0 z-50">
    <div class="drawer">
      <input id="my-drawer-4" type="checkbox" class="drawer-toggle"/>
      <div class="drawer-content">
        <nav class="navbar w-full bg-[#070D25] border-b border-gray-800">
          <label for="my-drawer-4" aria-label="open sidebar" class="btn btn-square btn-ghost hover:bg-slate-700 hover:border-slate-800 hover:shadow-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="white"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /> </svg>
          </label>

          <div class="px-4 ml-auto mr-auto">
            <div class="px-4 ml-auto mr-auto"><img src="<?= LINKS_PATH . "/imagenes/logo.png" ?> " alt="Logo ForexFalcon" width="68px"></div>
          </div>

          <button class="btn bg-[#F38331] hover:bg-[#E06E20] text-white border-none shadow-lg hover:shadow-xl transition-all duration-300 rounded-xl font-semibold hidden lg:block mr-3" type="button">Iniciar sesión</button>
          <button class="btn bg-[#F38331] hover:bg-[#E06E20] text-white border-none shadow-lg hover:shadow-xl transition-all duration-300 rounded-xl font-semibold hidden lg:block" type="button">Registrarte</button>
        </nav>
      </div>

      <!-- Navegación lateral (drawer) -->
      <div class="drawer-side">
        <label for="my-drawer-4" aria-label="close sidebar" class="drawer-overlay"></label>
        <div class="flex min-h-full flex-col items-start bg-slate-800 w-64">
          <ul class="menu w-full grow p-4">
            <li class="mb-1">
              <details>
                <summary class="text-white hover:bg-slate-700 rounded-lg"><span class="mr-2"><i class="fas fa-chart-line"></i></span> Servicios</summary>
                <ul class="ml-4 mt-2">
                  <li><a class="text-gray-300 hover:text-white"><i class="fas fa-copy mr-2"></i>CopyTrading</a></li>
                  <li><a class="text-gray-300 hover:text-white"><i class="fas fa-chalkboard-user mr-2"></i>Mentorias</a></li>
                  <li><a class="text-gray-300 hover:text-white"><i class="fas fa-robot mr-2"></i>Bots</a></li>
                  <li><a class="text-gray-300 hover:text-white"><i class="fas fa-chart-simple mr-2"></i>Análisis</a></li>
                </ul>
              </details>
            </li>
            <li class="mb-1"><button class="text-white hover:bg-slate-700 w-full text-left"><i class="fas fa-users mr-3"></i>Quiénes somos</button></li>
            <li class="mb-1"><button class="text-white hover:bg-slate-700 w-full text-left"><i class="fas fa-book mr-3"></i>Catálogo</button></li>
            <li class="mb-1"><button class="text-white hover:bg-slate-700 w-full text-left"><i class="fas fa-question-circle mr-3"></i>Ayuda</button></li>
            <li class="mt-3 flex flex-col gap-2">
              <button class="btn bg-[#F38331] hover:bg-[#E06E20] text-white border-none shadow-lg hover:shadow-xl transition-all duration-300 rounded-xl font-semibold block lg:hidden" type="button">Iniciar sesión</button>
              <button class="btn bg-[#F38331] hover:bg-[#E06E20] text-white border-none shadow-lg hover:shadow-xl transition-all duration-300 rounded-xl font-semibold block lg:hidden" type="button">Registrarte</button>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </header>

  <main>

    <!-- ===== HERO SECTION ===== -->
    <section class="relative bg-gradient-to-br from-[#070D25] to-[#1a1f3a] text-white overflow-hidden pt-10 pb-10">
      
      <div class="container mx-auto px-4 py-16 lg:py-24 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12">
          
          <div class="flex-1 text-center lg:text-left">
            
            <h1 class="text-4xl lg:text-6xl xl:text-7xl font-black leading-tight mb-6">
              Tu libertad financiera <br>
              <span class="text-[#d66a1e]">comienza aquí</span>
            </h1>
            
            <p class="text-white/80 text-lg lg:text-xl leading-relaxed mb-8 max-w-2xl mx-auto lg:mx-0">
              En <span class="font-bold text-white">ForexFalcon</span> no solo aprendes trading. Te acompañamos paso a paso con <span class="font-semibold text-[#d66a1e]">asistencia 1:1</span> para que operes con seguridad y alcances la libertad financiera de forma cómoda, sin importar tu experiencia previa.
            </p>
            
            <div class="flex flex-wrap gap-4 justify-center lg:justify-start">
              <button class="btn bg-[#d66a1e] hover:bg-[#b8540f] text-white border-none shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 px-8 py-3 rounded-xl font-semibold text-lg">
                <i class="fas fa-play mr-2"></i> Comienza ahora
              </button>
            </div>
          </div>
          
          <div class="flex-1 flex justify-center">
            <div class="relative group max-w-md">
              <div class="absolute -inset-1 bg-gradient-to-r from-[#d66a1e] to-[#5A9C32] rounded-3xl blur-2xl opacity-40 group-hover:opacity-60 transition-opacity duration-500"></div>
              <div class="relative bg-white/10 backdrop-blur-sm rounded-3xl p-2 shadow-2xl">
                <img class="relative rounded-2xl w-full transform transition-transform duration-500 group-hover:scale-105" src="<?= LINKS_PATH . "/imagenes/movil.png" ?>" alt="App ForexFalcon" width="500">
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </section>

    <!-- ===== SECCIÓN MISIÓN, VISIÓN Y VALORES (TABS MEJORADOS) ===== -->
    <section class="py-20 px-4">
      <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
          <h2 class="text-4xl lg:text-5xl font-bold text-[#070D25]">Nuestra <span class="text-[#d66a1e]">esencia</span></h2>
          <p class="text-gray-600 text-lg mt-4 max-w-2xl mx-auto">Más que una academia, somos tu socio en el camino hacia la libertad financiera.</p>
        </div>
        
        <div class="max-w-5xl mx-auto">
          <!-- Pestañas DaisyUI con estilo personalizado -->
          <div role="tablist" class="tabs tabs-boxed bg-gray-200/80 p-2 rounded-xl shadow-lg justify-center 
                      [&_.tab-active]:bg-[#d66a1e] [&_.tab-active]:text-white [&_.tab-active]:shadow-md 
                      [&_.tab]:transition-all [&_.tab]:duration-300 [&_.tab]:focus:outline-none [&_.tab]:focus:ring-2 
                      [&_.tab]:focus:ring-[#d66a1e] [&_.tab]:focus:ring-offset-2">
            
            <!-- Pestaña Misión -->
            <input type="radio" name="mv_tabs" role="tab" class="tab text-lg font-semibold text-[#070D25]" aria-label="Misión" checked />
            <div role="tabpanel" class="tab-content bg-gradient-to-br from-white via-white to-[#5A9C32]/5 rounded-xl p-8 shadow-xl border border-gray-200/60 mt-4">
              <div class="flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-[#d66a1e]/20 to-[#5A9C32]/20 flex items-center justify-center mb-4 ring-4 ring-[#d66a1e]/30 shadow-inner">
                  <i class="fas fa-bullseye text-4xl text-[#d66a1e]"></i>
                </div>
                <p class="text-gray-800 leading-relaxed text-lg">
                  Formar traders autónomos y financieramente libres mediante una enseñanza cercana, 
                  <span class="font-semibold text-[#d66a1e]">asistencia personalizada 1:1</span> y herramientas innovadoras. 
                  En ForexFalcon no solo entregamos conocimientos, sino que construimos confianza, 
                  disciplina y resultados sostenibles en cada alumno.
                </p>
              </div>
            </div>

            <!-- Pestaña Visión -->
            <input type="radio" name="mv_tabs" role="tab" class="tab text-lg font-semibold text-[#070D25]" aria-label="Visión" />
            <div role="tabpanel" class="tab-content bg-gradient-to-br from-white via-white to-[#5A9C32]/5 rounded-xl p-8 shadow-xl border border-gray-200/60 mt-4">
              <div class="flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-[#d66a1e]/20 to-[#5A9C32]/20 flex items-center justify-center mb-4 ring-4 ring-[#d66a1e]/30 shadow-inner">
                  <i class="fas fa-eye text-4xl text-[#d66a1e]"></i>
                </div>
                <p class="text-gray-800 leading-relaxed text-lg">
                  Ser la academia de trading <span class="font-semibold text-[#d66a1e]">referente en Latinoamérica y España</span>, 
                  reconocida por combinar tecnología de vanguardia con una mentoría humana única. 
                  Aspiramos a crear una comunidad donde cada alumno transforme su relación con el dinero.
                </p>
              </div>
            </div>

            <!-- Pestaña Valores -->
            <input type="radio" name="mv_tabs" role="tab" class="tab text-lg font-semibold text-[#070D25]" aria-label="Valores" />
            <div role="tabpanel" class="tab-content bg-gradient-to-br from-white via-white to-[#5A9C32]/5 rounded-xl p-8 shadow-xl border border-gray-200/60 mt-4">
              <div class="flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-[#d66a1e]/20 to-[#5A9C32]/20 flex items-center justify-center mb-4 ring-4 ring-[#d66a1e]/30 shadow-inner">
                  <i class="fas fa-gem text-4xl text-[#d66a1e]"></i>
                </div>
                <div class="grid grid-cols-1 gap-3 text-left w-full mt-2">
                  <div class="flex items-start gap-2"><i class="fa-solid fa-caret-right text-[#d66a1e] mt-1 w-5"></i><span><strong class="text-[#d66a1e]">Compromiso 1:1:</strong> Asistencia personalizada, cada alumno es único.</span></div>
                  <div class="flex items-start gap-2"><i class="fa-solid fa-caret-right text-[#d66a1e] mt-1 w-5"></i><span><strong class="text-[#d66a1e]">Transparencia:</strong> Estrategias reales y resultados sin promesas falsas.</span></div>
                  <div class="flex items-start gap-2"><i class="fa-solid fa-caret-right text-[#d66a1e] mt-1 w-5"></i><span><strong class="text-[#d66a1e]">Innovación constante:</strong> Bots automáticos, copy trading y análisis semanal.</span></div>
                  <div class="flex items-start gap-2"><i class="fa-solid fa-caret-right text-[#d66a1e] mt-1 w-5"></i><span><strong class="text-[#d66a1e]">Comunidad colaborativa:</strong> Aprendizaje colectivo con respaldo del mentor.</span></div>
                  <div class="flex items-start gap-2"><i class="fa-solid fa-caret-right text-[#d66a1e] mt-1 w-5"></i><span><strong class="text-[#d66a1e]">Libertad financiera:</strong> Enfoque en resultados sostenibles, no solo teoría.</span></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== SECCIÓN SERVICIOS (ACTUALIZADA CON PRECIOS REALES) ===== -->
    <section class="py-20 px-4 bg-white">
      <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
          <span class="text-[#d66a1e] font-semibold tracking-wider">LO QUE OFRECEMOS</span>
          <h2 class="text-4xl lg:text-5xl font-bold text-[#070D25] mt-2">Nuestros <span class="text-[#d66a1e]">servicios</span></h2>
          <p class="text-gray-600 text-lg mt-4 max-w-2xl mx-auto">Soluciones diseñadas para cada nivel, desde principiante hasta experto.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          
          <!-- CopyTrading -->
          <div class="card bg-gradient-to-br from-[#070D25] to-[#0f1633] text-white shadow-2xl rounded-2xl transition-all duration-300 hover:scale-105 hover:shadow-[#d66a1e]/20 hover:shadow-xl border border-white/10 card-hover">
            <div class="card-body p-6 md:p-8">
              <div class="w-16 h-16 rounded-full bg-[#d66a1e]/20 flex items-center justify-center mb-4 ring-2 ring-[#d66a1e]/40">
                <i class="fa-solid fa-copy text-3xl text-[#d66a1e]"></i>
              </div>
              <h3 class="card-title text-2xl font-bold">CopyTrading</h3>
              <div class="badge bg-[#5A9C32] text-white border-0 py-3 px-4 text-base font-bold my-3">TOTALMENTE GRATIS</div>
              <p class="text-white/80 leading-relaxed">
                Copia automáticamente mis operaciones en tiempo real. Sin experiencia, sin estrés.
              </p>
              <ul class="mt-4 space-y-2 text-sm">
                <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#5A9C32]"></i> Depósito mínimo $100</li>
                <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#5A9C32]"></i> Configuración en 5 minutos</li>
                <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#5A9C32]"></i> Sin comisiones adicionales</li>
              </ul>
              <div class="card-actions mt-6">
                <button class="btn bg-[#d66a1e] hover:bg-[#b8540f] text-white border-0 w-full rounded-full shadow-md">Activar ahora <i class="fa-solid fa-arrow-right ml-2"></i></button>
              </div>
            </div>
          </div>

          <!-- Mentorías -->
          <div class="card bg-gradient-to-br from-[#070D25] to-[#0f1633] text-white shadow-2xl rounded-2xl transition-all duration-300 hover:scale-105 hover:shadow-[#d66a1e]/20 hover:shadow-xl border border-white/10 card-hover">
            <div class="card-body p-6 md:p-8">
              <div class="w-16 h-16 rounded-full bg-[#d66a1e]/20 flex items-center justify-center mb-4 ring-2 ring-[#d66a1e]/40">
                <i class="fa-solid fa-chalkboard-user text-3xl text-[#d66a1e]"></i>
              </div>
              <h3 class="card-title text-2xl font-bold">Mentorías 1:1</h3>
              <p class="text-white/80 leading-relaxed mt-2">
                Aprende mi estrategia 100% funcional con acompañamiento personalizado.
              </p>
              <div class="mt-4 space-y-1 text-sm">
                <p class="font-semibold">Planes disponibles:</p>
                <div class="flex justify-between"><span>1 Mes</span> <span class="font-bold text-[#d66a1e]">$150</span></div>
                <div class="flex justify-between"><span>3 Meses</span> <span class="font-bold text-[#d66a1e]">$350</span></div>
                <div class="flex justify-between"><span>1 Año</span> <span class="font-bold text-[#d66a1e]">$999</span></div>
              </div>
              <p class="text-xs text-white/60 mt-2">* En 1 año aprendes todo sobre trading de forma completa.</p>
              <div class="card-actions mt-6">
                <button class="btn bg-[#d66a1e] hover:bg-[#b8540f] text-white border-0 w-full rounded-full shadow-md">Elegir plan <i class="fa-solid fa-arrow-right ml-2"></i></button>
              </div>
            </div>
          </div>

          <!-- Bots -->
          <div class="card bg-gradient-to-br from-[#070D25] to-[#0f1633] text-white shadow-2xl rounded-2xl transition-all duration-300 hover:scale-105 hover:shadow-[#d66a1e]/20 hover:shadow-xl border border-white/10 card-hover">
            <div class="card-body p-6 md:p-8">
              <div class="w-16 h-16 rounded-full bg-[#d66a1e]/20 flex items-center justify-center mb-4 ring-2 ring-[#d66a1e]/40">
                <i class="fa-solid fa-robot text-3xl text-[#d66a1e]"></i>
              </div>
              <h3 class="card-title text-2xl font-bold">Bots de Trading</h3>
              <div class="badge bg-[#d66a1e] text-white border-0 py-3 px-4 text-base font-bold my-3">$75 / mes</div>
              <p class="text-white/80 leading-relaxed">
                Algoritmos automatizados que operan por ti 24/7 de lunes a viernes.
              </p>
              <ul class="mt-4 space-y-2 text-sm">
                <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#5A9C32]"></i> Opera sin descanso</li>
                <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#5A9C32]"></i> Basado en mi estrategia</li>
                <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#5A9C32]"></i> Resultados consistentes</li>
              </ul>
              <div class="card-actions mt-6">
                <button class="btn bg-[#d66a1e] hover:bg-[#b8540f] text-white border-0 w-full rounded-full shadow-md">Adquirir bot <i class="fa-solid fa-arrow-right ml-2"></i></button>
              </div>
            </div>
          </div>

          <!-- Análisis -->
          <div class="card bg-gradient-to-br from-[#070D25] to-[#0f1633] text-white shadow-2xl rounded-2xl transition-all duration-300 hover:scale-105 hover:shadow-[#d66a1e]/20 hover:shadow-xl border border-white/10 card-hover">
            <div class="card-body p-6 md:p-8">
              <div class="w-16 h-16 rounded-full bg-[#d66a1e]/20 flex items-center justify-center mb-4 ring-2 ring-[#d66a1e]/40">
                <i class="fa-solid fa-chart-simple text-3xl text-[#d66a1e]"></i>
              </div>
              <h3 class="card-title text-2xl font-bold">Análisis Premium</h3>
              <div class="badge bg-[#d66a1e] text-white border-0 py-3 px-4 text-base font-bold my-3">$50 / mes</div>
              <p class="text-white/80 leading-relaxed">
                Grupo exclusivo con mis predicciones diarias sobre materias primas y divisas.
              </p>
              <ul class="mt-4 space-y-2 text-sm">
                <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#5A9C32]"></i> Señales claras de entrada/salida</li>
                <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#5A9C32]"></i> Análisis fundamental y técnico</li>
                <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#5A9C32]"></i> Soporte directo en el grupo</li>
              </ul>
              <div class="card-actions mt-6">
                <button class="btn bg-[#d66a1e] hover:bg-[#b8540f] text-white border-0 w-full rounded-full shadow-md">Unirse al grupo <i class="fa-solid fa-arrow-right ml-2"></i></button>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- ===== CTA FINAL ===== -->
    <section class="py-20 px-4 bg-gradient-to-r from-[#070D25] to-[#1a1f3a] text-white">
      <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-3xl lg:text-5xl font-bold mb-6">¿Listo para cambiar tu vida financiera?</h2>
        <p class="text-white/80 text-lg mb-10 max-w-2xl mx-auto">Únete hoy a ForexFalcon y descubre cómo el trading puede convertirse en tu camino hacia la libertad.</p>
        <div class="flex flex-wrap gap-4 justify-center">
          <button class="btn bg-[#d66a1e] hover:bg-[#b8540f] text-white border-none shadow-xl hover:shadow-2xl transition-all duration-300 px-8 py-3 rounded-xl font-semibold text-lg">
            <i class="fas fa-rocket mr-2"></i> Comienza tu viaje
          </button>
        </div>
      </div>
    </section>

  </main>

  <!-- ===== FOOTER (SIN MODIFICACIONES) ===== -->
  <footer class="footer sm:footer-horizontal bg-[#070D25] text-base-content p-10 shadow-[0_-25px_50px_10px_rgba(7,13,37,0.4)]">
    <section>
      <img src="<?= LINKS_PATH . "/imagenes/logo.png" ?> " alt="Logo ForexFalcon" width="100px">
    </section>
    <div>
      <h6 class="footer-title text-white">Catálogo</h6>
      <a class="link link-hover text-white">CopyTrading</a>
      <a class="link link-hover text-white">Mentorias</a>
      <a class="link link-hover text-white">Bots</a>
      <a class="link link-hover text-white">Análisis</a>
    </div>
    <div>
      <h6 class="footer-title text-white">Nosotros</h6>
      <a class="link link-hover text-white">Quienes somos</a>
      <a class="link link-hover text-white">Contacto</a>
    </div>
    <div>
      <h6 class="footer-title text-white">Redes</h6>
      <a class="link link-hover text-white">Instagram</a>
      <a class="link link-hover text-white">TikTok</a>
      <a class="link link-hover text-white">Telegram</a>
    </div>
  </footer>
</body>
</html>