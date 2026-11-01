/* ═══════════════════════════════════════════════════════
   PÁGINA DE SERVICIO (/servicio?id=...)
   · Lista de vídeos: al elegir uno se carga en el reproductor.
   · Botón "Abrir el chat" (servicios de tipo chat).
   Los nombres se leen de atributos data-* y se pintan con textContent.
   ═══════════════════════════════════════════════════════ */

(function () {
  'use strict';

  // ── Servicios de tipo chat: abre la burbuja de mensajería ──
  document.querySelectorAll('[data-abrir-chat]').forEach(function (boton) {
    boton.addEventListener('click', function () {
      var burbuja = document.getElementById('ffChatLauncher');
      if (burbuja && burbuja.getAttribute('aria-expanded') !== 'true') burbuja.click();
    });
  });

  // ── Servicios de tipo vídeo ──
  var contenedor = document.querySelector('.servicio-video');
  var reproductor = document.getElementById('reproductor');
  if (!contenedor || !reproductor) return;

  var urlBase = contenedor.getAttribute('data-url-base');
  var titulo = document.getElementById('videoTitulo');
  var meta = document.getElementById('videoMeta');
  var error = document.getElementById('videoError');
  var items = contenedor.querySelectorAll('.video-item');

  reproductor.addEventListener('error', function () { error.hidden = false; });
  reproductor.addEventListener('loadeddata', function () { error.hidden = true; });

  function elegir(item) {
    Array.prototype.forEach.call(items, function (i) {
      i.classList.remove('activo');
      i.removeAttribute('aria-current');
    });
    item.classList.add('activo');
    item.setAttribute('aria-current', 'true');

    error.hidden = true;
    reproductor.src = urlBase + encodeURIComponent(item.getAttribute('data-id'));
    reproductor.load();
    var p = reproductor.play();
    if (p && p.catch) p.catch(function () { /* el navegador pidió pulsar play: no es un error */ });

    titulo.textContent = item.getAttribute('data-nombre');
    meta.textContent = item.getAttribute('data-meta');

    // En móvil la lista queda debajo: se sube al reproductor para verlo
    if (window.innerWidth < 992) reproductor.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }

  Array.prototype.forEach.call(items, function (item) {
    item.addEventListener('click', function () { elegir(item); });
  });
})();
