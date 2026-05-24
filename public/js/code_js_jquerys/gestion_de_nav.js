"user strict";

///////////////////
// Functions
///////////////////

const onScroll = () => {
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
const btnCatalogo = document.querySelector("#btnCatalogo");
const btnEsencia = document.querySelector("#btnEsencia");
const btnFAQ = document.querySelector("#btnFAQ");
const btnCompañia = document.querySelector("#btnCompañia");

btnCatalogo.addEventListener("click", e => {
  btnCompañia.classList.remove("green","fw-bolder");
  btnEsencia.classList.remove("green","fw-bolder");
  btnCatalogo.classList.add("green","fw-bolder");
  btnFAQ.classList.remove("green","fw-bolder");
})

btnEsencia.addEventListener("click", e => {
  btnCatalogo.classList.remove("green","fw-bolder");
  btnCompañia.classList.remove("green","fw-bolder");
  btnEsencia.classList.add("green","fw-bolder");
  btnFAQ.classList.remove("green","fw-bolder");
})

btnFAQ.addEventListener("click", e => {
  btnCatalogo.classList.remove("green","fw-bolder");
  btnEsencia.classList.remove("green","fw-bolder");
  btnCompañia.classList.add("green","fw-bolder");
  btnFAQ.classList.add("green","fw-bolder");
})

// ── Dropdown animado ────────────────────────────────────────
document.querySelectorAll('.dropdown').forEach(dropdown => {
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
});

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