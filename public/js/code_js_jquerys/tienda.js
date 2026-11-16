/* Tienda (/tienda): aparición animada al hacer scroll, filtros por tipo y botones que abren el chat. */
(function () {
  'use strict';

  // ── Botones/enlaces que abren la burbuja de mensajería ──
  document.querySelectorAll('[data-abrir-chat]').forEach(function (el) {
    el.addEventListener('click', function (ev) {
      ev.preventDefault();
      var burbuja = document.getElementById('ffChatLauncher');
      if (burbuja && burbuja.getAttribute('aria-expanded') !== 'true') burbuja.click();
    });
  });

  var raiz = document.querySelector('.tienda');
  if (!raiz) return;

  // ── Aparición escalonada (si el navegador no lo soporta, todo se ve normal) ──
  var items = raiz.querySelectorAll('[data-reveal]');
  if ('IntersectionObserver' in window && items.length) {
    raiz.classList.add('js-anim');
    var obs = new IntersectionObserver(function (entradas) {
      entradas.forEach(function (e) {
        if (!e.isIntersecting) return;
        var el = e.target;
        el.classList.add('visible');
        obs.unobserve(el);
        setTimeout(function () { el.classList.add('hecho'); }, 1300);
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -30px 0px' });
    items.forEach(function (el) { obs.observe(el); });
  }

  // ── Filtros por tipo de servicio ──
  var chips = raiz.querySelectorAll('.tienda-chip');
  var tarjetas = raiz.querySelectorAll('.tienda-card');
  var vacio = document.getElementById('tiendaSinResultados');

  chips.forEach(function (chip) {
    chip.addEventListener('click', function () {
      var filtro = chip.getAttribute('data-filtro');
      chips.forEach(function (c) { c.classList.toggle('activo', c === chip); });

      var visibles = 0;
      tarjetas.forEach(function (t) {
        var coincide = filtro === 'todos' || t.getAttribute('data-tipo') === filtro;
        t.hidden = !coincide;
        if (coincide) {
          // Reinicia la animación de entrada para que las tarjetas "reaparezcan" con fluidez
          t.classList.remove('reaparece');
          void t.offsetWidth;
          t.style.setProperty('--r', visibles);
          t.classList.add('reaparece');
          visibles++;
        }
      });
      if (vacio) vacio.hidden = visibles !== 0;
    });
  });
})();
