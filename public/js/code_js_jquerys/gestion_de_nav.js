"user strict";

///////////////////
// Functions
///////////////////

function onScroll() {
  const scrollY = window.scrollY || window.pageYOffset;
  const heroHeight = hero.offsetHeight;

  if (scrollY < 80) {
    nav.classList.remove('nav--bubble');
  } else {
    nav.classList.add('nav--bubble');
  }

  const blurStart = heroHeight * 0.55;
  const progress = Math.max(0, Math.min(1, (scrollY - blurStart) / (heroHeight - blurStart)));
  blurBottom.style.height = Math.round(progress * 220) + 'px';
  blurBottom.style.backdropFilter = `blur(${Math.round(progress * 18)}px)`;
  blurBottom.style.webkitBackdropFilter = `blur(${Math.round(progress * 18)}px)`;
}

///////////////////
// Main
///////////////////

const nav = document.querySelector('#navegacion');
const blurBottom = document.querySelector('#heroBlurBottom');
const hero = document.querySelector('.hero');

const offcanvas = document.getElementById('offcanvasNavbar');
offcanvas.addEventListener('show.bs.offcanvas', () => {
  nav.style.zIndex = '1030'; // navbar detrás del offcanvas al abrir
});
offcanvas.addEventListener('hidden.bs.offcanvas', () => {
  nav.style.zIndex = '1040'; // restaura al cerrar
  onScroll();
});

window.addEventListener('scroll', onScroll, { passive: true });
onScroll();