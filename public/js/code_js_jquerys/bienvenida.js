/* Bienvenida (/recursos): botones que abren la burbuja de chat + animaciones de entrada al hacer scroll. */
(function () {
  'use strict';

  document.querySelectorAll('[data-abrir-chat]').forEach(function (boton) {
    boton.addEventListener('click', function () {
      var burbuja = document.getElementById('ffChatLauncher');
      if (burbuja && burbuja.getAttribute('aria-expanded') !== 'true') burbuja.click();
    });
  });

  var raiz = document.querySelector('.bienvenida');
  var items = document.querySelectorAll('.bienvenida [data-reveal]');
  if (!raiz || !items.length || !('IntersectionObserver' in window)) return;

  raiz.classList.add('js-anim');
  var obs = new IntersectionObserver(function (entradas) {
    entradas.forEach(function (e) {
      if (!e.isIntersecting) return;
      var el = e.target;
      el.classList.add('visible');
      obs.unobserve(el);
      setTimeout(function () { el.classList.add('hecho'); }, 1300);
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -30px 0px' });
  items.forEach(function (el) { obs.observe(el); });
})();
