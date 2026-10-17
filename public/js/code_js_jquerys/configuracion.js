/* ═══════════════════════════════════════════════════════
   CONFIGURACIÓN — Modal generado íntegramente por JS
   Construido con la API del DOM (createElement/appendChild),
   sin innerHTML en ningún punto.
   ═══════════════════════════════════════════════════════ */

document.addEventListener('DOMContentLoaded', function () {
  const btnConfig = document.getElementById('btnAbrirConfiguracion');
  if (!btnConfig) return;

  btnConfig.addEventListener('click', function (e) {
    e.preventDefault();
    abrirModalConfiguracion();
  });
});

/* ── Helper: crea un elemento con atributos/clases/hijos ──
   No usa innerHTML en ningún momento — solo createElement,
   setAttribute, classList.add y appendChild por debajo. */
function crearEl(tag, opciones, hijos) {
  const elemento = document.createElement(tag);

  if (opciones) {
    if (opciones.clases) {
      opciones.clases.forEach(function (c) { elemento.classList.add(c); });
    }
    if (opciones.texto !== undefined) {
      elemento.textContent = opciones.texto;
    }
    if (opciones.atributos) {
      for (const nombre in opciones.atributos) {
        elemento.setAttribute(nombre, opciones.atributos[nombre]);
      }
    }
    if (opciones.id) {
      elemento.id = opciones.id;
    }
  }

  if (hijos) {
    hijos.forEach(function (hijo) {
      if (hijo) elemento.appendChild(hijo);
    });
  }

  return elemento;
}

/**
 * Construye el modal (si no existe todavía), lo inserta en el body
 * y lo muestra. Si ya existe de una apertura anterior, lo reutiliza.
 */
function abrirModalConfiguracion() {
  let modalEl = document.getElementById('modalConfiguracion');

  if (!modalEl) {
    modalEl = crearModalConfiguracion();
    document.body.appendChild(modalEl);
    inicializarTabsConfiguracion(modalEl);
    poblarSelectsPaisTelefono(modalEl);
  }

  const instancia = bootstrap.Modal.getOrCreateInstance(modalEl);
  instancia.show();
}

/* ── Construcción del modal, pieza por pieza ────────────── */

function crearModalConfiguracion() {
  return crearEl('div', {
    id: 'modalConfiguracion',
    clases: ['modal', 'fade'],
    atributos: { tabindex: '-1', 'aria-hidden': 'true' }
  }, [
    crearEl('div', { clases: ['modal-dialog', 'modal-dialog-centered', 'config-modal-dialog'] }, [
      crearEl('div', { clases: ['modal-content', 'config-modal-content'] }, [
        crearHeaderModal(),
        crearBodyModal()
      ])
    ])
  ]);
}

function crearHeaderModal() {
  const icono = crearEl('i', { clases: ['fa-solid', 'fa-xmark'] });

  const botonCerrar = crearEl('button', {
    clases: ['config-modal-close'],
    atributos: { type: 'button', 'data-bs-dismiss': 'modal', 'aria-label': 'Cerrar' }
  }, [icono]);

  const titulo = crearEl('h5', { clases: ['config-modal-title'], texto: 'Configuración' });

  return crearEl('div', { clases: ['config-modal-header'] }, [titulo, botonCerrar]);
}

function crearBodyModal() {
  return crearEl('div', { clases: ['config-modal-body'] }, [
    crearNavModal(),
    crearPanelesModal()
  ]);
}

function crearNavModal() {
  const itemCuenta = crearNavItem('cuenta', 'fa-user', 'Información de la cuenta', 'Datos personales', true);
  const itemSeguridad = crearNavItem('seguridad', 'fa-shield-halved', 'Seguridad', 'Contraseña y accesos', false);

  return crearEl('div', { clases: ['config-modal-nav'] }, [itemCuenta, itemSeguridad]);
}

function crearNavItem(tab, claseIcono, tituloTexto, subtituloTexto, activo) {
  const icono = crearEl('i', { clases: ['fa-solid', claseIcono] });
  const iconoWrap = crearEl('span', { clases: ['config-nav-icon'] }, [icono]);

  const titulo = crearEl('strong', { texto: tituloTexto });
  const subtitulo = crearEl('small', { texto: subtituloTexto });
  const textoWrap = crearEl('span', { clases: ['config-nav-text'] }, [titulo, subtitulo]);

  const boton = crearEl('button', {
    clases: activo ? ['config-nav-item', 'active'] : ['config-nav-item'],
    atributos: { type: 'button' }
  }, [iconoWrap, textoWrap]);

  boton.dataset.tab = tab;

  return boton;
}

function crearPanelesModal() {
  const panelCuenta = crearEl('div', {
    id: 'panel-cuenta',
    clases: ['config-panel', 'active']
  }, [crearFormularioCuenta()]);

  const panelSeguridad = crearEl('div', {
    id: 'panel-seguridad',
    clases: ['config-panel']
  });

  return crearEl('div', { clases: ['config-modal-content-panel'] }, [panelCuenta, panelSeguridad]);
}

function crearFormularioCuenta() {
  const grid = crearEl('div', { clases: ['config-form-grid'] }, [
    crearCampoInput('cfg-nombre', 'nombre', 'text', 'Nombre', 'Tu nombre'),
    crearCampoInput('cfg-apellido', 'apellido', 'text', 'Apellido', 'Tu apellido'),
    crearCampoInput('cfg-email', 'email', 'email', 'Correo electrónico', 'correo@ejemplo.com', true),
    crearCampoTelefono(),
    crearCampoPais()
  ]);

  const iconoGuardar = crearEl('i', { clases: ['fa-solid', 'fa-check'] });
  const botonGuardar = crearEl('button', {
    clases: ['config-btn-guardar'],
    atributos: { type: 'submit' }
  }, [iconoGuardar, document.createTextNode(' Guardar cambios')]);

  const acciones = crearEl('div', { clases: ['config-form-actions'] }, [botonGuardar]);

  return crearEl('form', { clases: ['config-form'] }, [grid, acciones]);
}

function crearCampoInput(id, name, tipo, etiquetaTexto, placeholder, esAncho) {
  const label = crearEl('label', { texto: etiquetaTexto, atributos: { for: id } });

  const input = crearEl('input', {
    id: id,
    atributos: { type: tipo, name: name, placeholder: placeholder }
  });

  const clases = esAncho ? ['config-field', 'config-field--full'] : ['config-field'];

  return crearEl('div', { clases: clases }, [label, input]);
}

function crearCampoPais() {
  const label = crearEl('label', { texto: 'País', atributos: { for: 'cfg-pais' } });

  const opcionDefault = crearEl('option', {
    texto: 'Selecciona un país',
    atributos: { value: '' }
  });

  const select = crearEl('select', { id: 'cfg-pais', atributos: { name: 'pais' } }, [opcionDefault]);

  return crearEl('div', { clases: ['config-field'] }, [label, select]);
}

function crearCampoTelefono() {
  const label = crearEl('label', { texto: 'Teléfono', atributos: { for: 'cfg-telefono' } });

  const selectPrefijo = crearEl('select', {
    id: 'cfg-tel-prefijo',
    clases: ['config-tel-prefijo'],
    atributos: { name: 'tel_prefijo' }
  });

  const inputTelefono = crearEl('input', {
    id: 'cfg-telefono',
    clases: ['config-tel-input'],
    atributos: { type: 'tel', name: 'telefono', placeholder: '600 000 000' }
  });

  const grupo = crearEl('div', { clases: ['config-tel-group'] }, [selectPrefijo, inputTelefono]);

  return crearEl('div', { clases: ['config-field'] }, [label, grupo]);
}

/* ── Interacción de pestañas (igual que antes) ─────────── */

function inicializarTabsConfiguracion(modal) {
  const navItems = modal.querySelectorAll('.config-nav-item');
  const panels = modal.querySelectorAll('.config-panel');

  navItems.forEach(function (btn) {
    btn.addEventListener('click', function () {
      cambiarTab(btn, navItems, panels);
    });
  });

  modal.addEventListener('hidden.bs.modal', function () {
    resetearAPrimeraPestaña(navItems, panels);
  });
}

function cambiarTab(btnSeleccionado, navItems, panels) {
  navItems.forEach(function (b) { b.classList.remove('active'); });
  panels.forEach(function (p) { p.classList.remove('active'); });

  btnSeleccionado.classList.add('active');

  const panelObjetivo = document.getElementById('panel-' + btnSeleccionado.dataset.tab);
  if (panelObjetivo) {
    panelObjetivo.classList.add('active');
  }
}

function resetearAPrimeraPestaña(navItems, panels) {
  if (navItems.length === 0) return;

  navItems.forEach(function (b) { b.classList.remove('active'); });
  panels.forEach(function (p) { p.classList.remove('active'); });

  navItems[0].classList.add('active');
  const primerPanel = document.getElementById('panel-' + navItems[0].dataset.tab);
  if (primerPanel) primerPanel.classList.add('active');
}


async function poblarSelectsPaisTelefono(modal) {
  const selectPais = modal.querySelector('#cfg-pais');
  const selectPrefijo = modal.querySelector('#cfg-tel-prefijo');
  if (!selectPais || !selectPrefijo) return;

  const { cargarPaises, getFlagEmoji } = await import('./paises.js');
  const datosPaises = await cargarPaises();

  const defaultPrefijo = document.createElement('option');
  defaultPrefijo.value = '';
  defaultPrefijo.textContent = '';
  selectPrefijo.appendChild(defaultPrefijo);

  for (const nombrePais in datosPaises) {
    const datos = datosPaises[nombrePais];

    const opcionPais = document.createElement('option');
    opcionPais.value = nombrePais;
    opcionPais.textContent = nombrePais;
    selectPais.appendChild(opcionPais);

    const opcionPrefijo = document.createElement('option');
    opcionPrefijo.value = nombrePais;
    opcionPrefijo.textContent = `${getFlagEmoji(datos.iso)} ${datos.prefijo}`;
    selectPrefijo.appendChild(opcionPrefijo);
  }

  // Sincronizar: país → prefijo
  selectPais.addEventListener('change', () => {
    if (selectPais.value) selectPrefijo.value = selectPais.value;
  });

  // Sincronizar: prefijo → país
  selectPrefijo.addEventListener('change', () => {
    if (selectPrefijo.value) selectPais.value = selectPrefijo.value;
  });
}