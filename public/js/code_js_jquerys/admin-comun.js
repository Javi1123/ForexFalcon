/* Administración (panel, usuarios, actividad): aparición animada, contadores y filtros que se envían solos. */
(function () {
  'use strict';

  var raiz = document.querySelector('.adm');
  if (!raiz) return;

  // ── Filtros: los selects y fechas envían el formulario al cambiar ──
  raiz.querySelectorAll('[data-autoenviar]').forEach(function (el) {
    el.addEventListener('change', function () { if (el.form) el.form.submit(); });
  });

  var reducir = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // ── Números que "suben" hasta su valor ──
  function contar(el) {
    var fin = parseInt(el.textContent, 10);
    if (isNaN(fin) || reducir || fin === 0) return;
    var inicio = null, dur = 900;
    function paso(t) {
      if (inicio === null) inicio = t;
      var p = Math.min(1, (t - inicio) / dur);
      el.textContent = Math.round(fin * (1 - Math.pow(1 - p, 3)));
      if (p < 1) requestAnimationFrame(paso); else el.textContent = fin;
    }
    el.textContent = '0';
    requestAnimationFrame(paso);
  }

  // ── Aparición escalonada al hacer scroll (sin soporte, todo se ve normal) ──
  var items = raiz.querySelectorAll('[data-reveal]');
  if (!('IntersectionObserver' in window)) { raiz.querySelectorAll('[data-contar]').forEach(contar); return; }

  raiz.classList.add('js-anim');
  var obs = new IntersectionObserver(function (entradas) {
    entradas.forEach(function (e) {
      if (!e.isIntersecting) return;
      var el = e.target;
      el.classList.add('visible');
      el.querySelectorAll('[data-contar]').forEach(contar);
      if (el.hasAttribute('data-contar')) contar(el);
      obs.unobserve(el);
      setTimeout(function () { el.classList.add('hecho'); }, 1300);
    });
  }, { threshold: 0.1, rootMargin: '0px 0px -30px 0px' });
  items.forEach(function (el) { obs.observe(el); });
})();
