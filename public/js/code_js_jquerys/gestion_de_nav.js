"user strict";

///////////////////
// Functions
///////////////////

function initMenuState() {
  const currentPath = window.location.pathname;
  const currentHash = window.location.hash;
  const currentPage = currentPath.split('/').pop() || 'index';

  clearActiveStates();

  if (currentHash || !isHomePage()) {
    const activeElement = determineActiveElement(currentPage, currentHash);
    if (activeElement) {
      setAsActive(activeElement);
    }
  }
}

function determineActiveElement(pageName, hash) {
  if (hash) {
    const hashLink = document.querySelector(`.nav-link[href*="${hash}"]`);
    if (hashLink) return hashLink;
  }

  const pageMapping = {
    'cursos': { type: 'nav-link', link: '/forexfalcon/cursos' },
    'requisitos': { type: 'nav-link', link: '.#requisitos' },
    'preguntas': { type: 'nav-link', link: '.#preguntas' },
    'donde_estamos': { type: 'dropdown', dropdown: 'nosotros', link: '/forexfalcon/donde_estamos' },
    'quienes_somos': { type: 'dropdown', dropdown: 'nosotros', link: '/forexfalcon/quienes_somos' },
    'contacto': { type: 'dropdown', dropdown: 'nosotros', link: '/forexfalcon/contacto' },
    'sugerencias': { type: 'dropdown', dropdown: 'nosotros', link: '/forexfalcon/sugerencias' }
  };
  
  const pageConfig = pageMapping[pageName];
  if (!pageConfig) return null;

  // Si es tipo nav-link (cursos, requisitos, FAQ)
  if (pageConfig.type === 'nav-link') {
    const specificLink = document.querySelector(`.nav-link[href="${pageConfig.link}"]`);
    if (specificLink) {
      return specificLink;
    }
    return null;
  }

  // Si es tipo dropdown (nosotros)
  let dropdownButton;
  if (pageConfig.dropdown === 'nosotros') {
    dropdownButton = document.querySelector('.nav-link.dropdown-toggle i.fa-users')?.closest('.nav-link');
  }

  if (dropdownButton) {
    const specificLink = document.querySelector(`.dropdown-link[href="${pageConfig.link}"]`);
    if (specificLink) {
      specificLink.classList.add('active');
    }
  }
  
  return dropdownButton;
}

function setupMenuEvents() {
  document.querySelectorAll('.nav-link:not(.dropdown-toggle)').forEach(link => {
    link.addEventListener('click', function(e) {
      if (!this.parentElement.classList.contains('dropdown')) {
        clearActiveStates();
        setAsActive(this);
        this.style.pointerEvents = 'none';
        this.style.cursor = 'default';
      }
    });
  });

  // Eventos para los enlaces de "nosotros" (dropdown)
  const nosotrosLinks = document.querySelectorAll('.nav-item.dropdown:last-child .dropdown-link');
  nosotrosLinks.forEach(link => {
    link.addEventListener('click', function(e) {
      clearActiveStates();

      const nosotrosBtn = document.querySelector('.nav-link.dropdown-toggle i.fa-users')?.closest('.nav-link');
      if (nosotrosBtn) {
        setAsActive(nosotrosBtn);

        nosotrosLinks.forEach(l => {
          l.style.pointerEvents = 'auto';
          l.style.cursor = 'pointer';
          l.classList.remove('active');
        });
          
        this.classList.add('active');
        this.style.pointerEvents = 'none';
        this.style.cursor = 'default';
      }
    });
  });
}

function setAsActive(element) {
  element.classList.add('active');
    
  if (element.classList.contains('dropdown-toggle') || element.classList.contains('nav-link') && element.parentElement.classList.contains('dropdown')) {
    element.style.pointerEvents = 'auto';
    element.style.cursor = 'pointer';
  } else {
    element.style.pointerEvents = 'none';
    element.style.cursor = 'default';
  }
}

function clearActiveStates() {
  document.querySelectorAll('.nav-link.active, .dropdown-link.active').forEach(item => {
    item.classList.remove('active');
    item.style.pointerEvents = 'auto';
    item.style.cursor = 'pointer';
  });
}

function isHomePage() {
  const path = window.location.pathname;
  const homePages = ['', '/', 'index_vista.php', 'aviso_legal.php', 'privacidad.php', 'mision_visionyvalores.php'];
  const currentPage = path.split('/').pop() || '';
  return homePages.includes(path) || homePages.includes(currentPage);
}

if (isHomePage() && !window.location.hash) {
  clearActiveStates();
}

///////////////////
// Main
///////////////////

initMenuState();

setupMenuEvents();