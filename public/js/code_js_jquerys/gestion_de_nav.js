"use strict";

///////////////////
// Config
///////////////////

const PAGE_MAP = {
  'quienes_somos': { zone: 'sobre',  color: 'orange' },
  'contacto':      { zone: 'centro', color: 'green'  },
};

///////////////////
// Utilidades
///////////////////

const getCurrentPage = () => window.location.pathname.split('/').pop() || '';
const getCurrentHash = () => window.location.hash;

const isHomePage = () => {
  const p = window.location.pathname;
  return ['', '/', 'index_vista.php'].includes(p) || ['', 'index_vista.php'].includes(getCurrentPage());
};

// Páginas que siempre deben mostrar el nav en formato bubble
const FORCE_BUBBLE_PAGES = ['quienes_somos', 'contacto'];

const shouldForceBubble = () => FORCE_BUBBLE_PAGES.includes(getCurrentPage());

///////////////////
// Estado activo
///////////////////

function clearActive() {
  for (const el of document.querySelectorAll('.nav-link, .dropdown-item')) {
    el.classList.remove('green', 'fw-bolder', 'orange');
  }
}

function setActive(el, color = 'green') {
  if (!el) return;
  el.classList.add(color, 'fw-bolder');
}

///////////////////
// Inicialización
///////////////////

function initMenuState() {
  clearActive();

  const page = getCurrentPage();
  const hash = getCurrentHash();

  // En home sin hash, nada activo
  if (isHomePage() && !hash) return;

  // Hash activo (Catálogo, Esencia, FAQs)
  if (hash) {
    const link = document.querySelector(`.nav-link[href*="${hash}"], .dropdown-item[href*="${hash}"]`);
    if (link) {
      const isSobre = link.classList.contains('zona-sobre');
      setActive(link, isSobre ? 'orange' : 'green');
      if (link.closest('#serviciosMovil')) setActive(btnCompañia, 'green');
      return;
    }
  }

  // Página externa activa
  const config = PAGE_MAP[page];
  if (config) {
    setActive(btnCompañia, 'green');

    // Buscar el item por texto o por href parcial
    let item = null;
    for (const el of document.querySelectorAll('#serviciosMovil .dropdown-item')) {
      if (el.getAttribute('href')?.includes(page)) {
        item = el;
        break;
      }
    }
    setActive(item, config.color);
    return;
  }

  // Fallback — cualquier link del nav que coincida
  for (const el of document.querySelectorAll('.nav-link, .dropdown-item')) {
    if (el.getAttribute('href')?.includes(page)) {
      const isSobre = el.classList.contains('zona-sobre');
      setActive(el, isSobre ? 'orange' : 'green');
      if (el.closest('#serviciosMovil')) setActive(btnCompañia, 'green');
      break;
    }
  }
}

///////////////////
// Eventos de clic
///////////////////

function setupEvents() {

  // Nav links principales
  for (const btn of [btnCatalogo, btnEsencia]) {
    btn?.addEventListener('click', () => {
      clearActive();
      setActive(btn, 'green');
    });
  }

  // FAQs
  document.querySelector('#btnFAQ')?.addEventListener('click', () => {
    clearActive();
    setActive(btnCompañia, 'green');
    setActive(document.querySelector('#btnFAQ'), 'green');
  });

  // Items del dropdown
  for (const item of document.querySelectorAll('#serviciosMovil .dropdown-item')) {
    item.addEventListener('click', () => {
      const isSobre = item.classList.contains('zona-sobre');
      clearActive();
      setActive(btnCompañia, isSobre ? 'orange' : 'green');
      setActive(item, isSobre ? 'orange' : 'green');
    });
  }
}

///////////////////
// Scroll / nav burbuja
///////////////////

const onScroll = () => {
  const scrollY = window.scrollY || window.pageYOffset;

  nav.classList.toggle('nav--bubble', scrollY >= 80 || shouldForceBubble());

  if (hero) {
    const heroHeight = hero.offsetHeight;
    const blurStart  = heroHeight * 0.55;
    const progress   = Math.max(0, Math.min(1, (scrollY - blurStart) / (heroHeight - blurStart)));
    blurBottom.style.height               = Math.round(progress * 220) + 'px';
    blurBottom.style.backdropFilter       = `blur(${Math.round(progress * 18)}px)`;
    blurBottom.style.webkitBackdropFilter = `blur(${Math.round(progress * 18)}px)`;
  }
};

///////////////////
// Dropdown animado
///////////////////

for (const dropdown of document.querySelectorAll('.dropdown')) {
  dropdown.addEventListener('show.bs.dropdown', () => {
    const menu = dropdown.querySelector('#serviciosMovil');
    if (!menu) return;
    if (window.innerWidth >= 992) menu.style.display = 'grid';
  });

  dropdown.addEventListener('hide.bs.dropdown', e => {
    const menu = dropdown.querySelector('#serviciosMovil');
    if (!menu || window.innerWidth < 992) return;
    e.preventDefault();
    menu.classList.remove('show');
    setTimeout(() => {
      menu.style.display = '';
      dropdown.querySelector('[data-bs-toggle="dropdown"]')
        .setAttribute('aria-expanded', 'false');
    }, 250);
  });
}

///////////////////
// Offcanvas
///////////////////

const offcanvas = document.getElementById('offcanvasNavbar');
offcanvas.addEventListener('show.bs.offcanvas', () => nav.style.zIndex = '1030');
offcanvas.addEventListener('hidden.bs.offcanvas', () => {
  nav.style.zIndex = '1040';
  onScroll();
});

///////////////////
// Compensar altura del nav fijo
///////////////////

const ajustarEspacioNav = () => {
  if (!nav) return;
  const altura = nav.offsetHeight;

  // Solo en páginas sin hero (donde el nav no flota sobre una imagen)
  if (!hero) {
    document.body.style.paddingTop = `${altura}px`;
  }
};

///////////////////
// Main
///////////////////

const nav         = document.querySelector('#navegacion');
const blurBottom  = document.querySelector('#heroBlurBottom');
const hero        = document.querySelector('.hero');
const btnCatalogo = document.querySelector('#btnCatalogo');
const btnEsencia  = document.querySelector('#btnEsencia');
const btnCompañia = document.querySelector('#btnCompañia');

initMenuState();
setupEvents();
ajustarEspacioNav();

window.addEventListener('scroll', onScroll, { passive: true });
window.addEventListener('resize', ajustarEspacioNav, { passive: true }); // <-- y aquí
onScroll();