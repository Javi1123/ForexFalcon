/* ═══════════════════════════════════════════════════════
   CONFIGURACIÓN — Modal generado íntegramente por JS
   Construido con la API del DOM (createElement/appendChild),
   sin innerHTML en ningún punto.

   Pestañas:
     · Información de la cuenta → datos personales (nombre, apellido,
                                  teléfono y país). El correo es el
                                  identificador de la cuenta: solo lectura.
     · Seguridad               → verificar correo (código por email) y
                                  cambiar contraseña.

   Es la misma ventana para usuarios y administradores: cada uno solo
   ve y cambia SU cuenta. Habla con /configuracion/* (JSON) usando
   window.FF_CFG = { base, csrf }, que imprime recursos/pie.php.
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
async function abrirModalConfiguracion() {
  let modalEl = document.getElementById('modalConfiguracion');

  if (!modalEl) {
    modalEl = crearModalConfiguracion();
    document.body.appendChild(modalEl);
    inicializarTabsConfiguracion(modalEl);
    inicializarSeguridadConfiguracion(modalEl);
    inicializarFormulariosConfiguracion(modalEl);
    modalEl._paisesListos = poblarSelectsPaisTelefono(modalEl);
  }

  const instancia = bootstrap.Modal.getOrCreateInstance(modalEl);
  instancia.show();

  // Los datos se piden cada vez que se abre (pueden haber cambiado), cuando los selects de país ya están llenos
  try { await modalEl._paisesListos; } catch (e) { /* sin lista de países se rellena igualmente el resto */ }
  cargarDatosConfiguracion(modalEl);
}

/* ── Construcción del modal, pieza por pieza ────────────── */

function crearModalConfiguracion() {
  return crearEl('div', {
    id: 'modalConfiguracion',
    clases: ['modal', 'fade'],
    atributos: { tabindex: '-1', 'aria-hidden': 'true', 'aria-labelledby': 'tituloModalConfiguracion' }
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

  const titulo = crearEl('h5', {
    id: 'tituloModalConfiguracion',
    clases: ['config-modal-title'],
    texto: 'Configuración'
  });

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

  return crearEl('div', {
    clases: ['config-modal-nav'],
    atributos: { role: 'tablist' }
  }, [itemCuenta, itemSeguridad]);
}

function crearNavItem(tab, claseIcono, tituloTexto, subtituloTexto, activo) {
  const icono = crearEl('i', { clases: ['fa-solid', claseIcono] });
  const iconoWrap = crearEl('span', { clases: ['config-nav-icon'] }, [icono]);

  const titulo = crearEl('strong', { texto: tituloTexto });
  const subtitulo = crearEl('small', { texto: subtituloTexto });
  const textoWrap = crearEl('span', { clases: ['config-nav-text'] }, [titulo, subtitulo]);

  const boton = crearEl('button', {
    clases: activo ? ['config-nav-item', 'active'] : ['config-nav-item'],
    atributos: {
      type: 'button',
      role: 'tab',
      'aria-selected': activo ? 'true' : 'false',
      'aria-controls': 'panel-' + tab
    }
  }, [iconoWrap, textoWrap]);

  boton.dataset.tab = tab;

  return boton;
}

function crearPanelesModal() {
  const panelCuenta = crearEl('div', {
    id: 'panel-cuenta',
    clases: ['config-panel', 'active'],
    atributos: { role: 'tabpanel' }
  }, [
    crearEncabezadoPanel(
      'Información de la cuenta',
      'Actualiza tus datos personales.',
      crearEl('span', { id: 'cfg-rol', clases: ['config-role'], atributos: { title: 'Tu rol en ForexFalcon' } })
    ),
    crearFormularioCuenta()
  ]);

  const panelSeguridad = crearEl('div', {
    id: 'panel-seguridad',
    clases: ['config-panel'],
    atributos: { role: 'tabpanel' }
  }, [
    crearEncabezadoPanel('Seguridad', 'Confirma tus datos de contacto y protege el acceso a tu cuenta.'),
    crearListaSeguridad()
  ]);

  return crearEl('div', { clases: ['config-modal-content-panel'] }, [panelCuenta, panelSeguridad]);
}

function crearEncabezadoPanel(tituloTexto, descripcionTexto, extra) {
  return crearEl('div', { clases: ['config-panel-head'] }, [
    crearEl('h6', { clases: ['config-panel-title'], texto: tituloTexto }),
    crearEl('p', { clases: ['config-panel-desc'], texto: descripcionTexto }),
    extra
  ]);
}

/* ── Pestaña: Información de la cuenta ──────────────────── */

function crearFormularioCuenta() {
  const grid = crearEl('div', { clases: ['config-form-grid'] }, [
    crearCampoInput('cfg-nombre', 'nombre', 'text', 'Nombre', 'Tu nombre'),
    crearCampoInput('cfg-apellido', 'apellido', 'text', 'Apellido', 'Tu apellido'),
    crearCampoCorreo(),
    crearCampoTelefono(),
    crearCampoPais()
  ]);

  const iconoGuardar = crearEl('i', { clases: ['fa-solid', 'fa-check'] });
  const botonGuardar = crearEl('button', {
    clases: ['config-btn-guardar'],
    atributos: { type: 'submit' }
  }, [iconoGuardar, document.createTextNode(' Guardar cambios')]);

  const mensaje = crearMensajeEstado();
  const acciones = crearEl('div', { clases: ['config-form-actions'] }, [mensaje, botonGuardar]);

  return crearEl('form', { id: 'cfg-form-cuenta', clases: ['config-form'], atributos: { novalidate: '' } }, [grid, acciones]);
}

/* Línea de estado de un formulario (éxito / error). Vacía = oculta. */
function crearMensajeEstado() {
  return crearEl('p', { clases: ['config-msg'], atributos: { role: 'status', 'aria-live': 'polite' } });
}

/* El correo identifica la cuenta (la usan los pagos, el chat y el acceso con Google):
   se muestra pero no se edita desde aquí. */
function crearCampoCorreo() {
  const campo = crearCampoInput('cfg-email', 'email', 'email', 'Correo electrónico', 'correo@ejemplo.com', true);
  const input = campo.querySelector('input');
  input.setAttribute('readonly', '');
  input.setAttribute('autocomplete', 'email');
  input.classList.add('config-control--readonly');
  campo.appendChild(crearEl('small', {
    clases: ['config-field-note'],
    texto: 'Es tu identificador de acceso y no se puede cambiar desde aquí. Si necesitas cambiarlo, escríbenos.'
  }));
  return campo;
}

function crearCampoInput(id, name, tipo, etiquetaTexto, placeholder, esAncho) {
  const label = crearEl('label', { texto: etiquetaTexto, atributos: { for: id } });

  const input = crearEl('input', {
    id: id,
    clases: ['config-control'],
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

  const select = crearEl('select', {
    id: 'cfg-pais',
    clases: ['config-control', 'config-control--select'],
    atributos: { name: 'pais' }
  }, [opcionDefault]);

  return crearEl('div', { clases: ['config-field'] }, [label, select]);
}

function crearCampoTelefono() {
  const label = crearEl('label', { texto: 'Teléfono', atributos: { for: 'cfg-telefono' } });

  const selectPrefijo = crearEl('select', {
    id: 'cfg-tel-prefijo',
    clases: ['config-tel-prefijo'],
    atributos: { name: 'tel_prefijo', 'aria-label': 'Prefijo del país' }
  });

  const inputTelefono = crearEl('input', {
    id: 'cfg-telefono',
    clases: ['config-tel-input'],
    atributos: { type: 'tel', name: 'telefono', placeholder: '600 000 000' }
  });

  const grupo = crearEl('div', { clases: ['config-tel-group'] }, [selectPrefijo, inputTelefono]);

  return crearEl('div', { clases: ['config-field'] }, [label, grupo]);
}

/* ── Pestaña: Seguridad ─────────────────────────────────── */

function crearListaSeguridad() {
  return crearEl('div', { clases: ['config-sec-list'] }, [
    crearTarjetaSeguridad({
      clave: 'email',
      icono: 'fa-envelope',
      titulo: 'Correo electrónico',
      descripcion: 'Confirma que tu correo es tuyo para recibir avisos y poder recuperar tu cuenta.',
      textoBoton: 'Verificar',
      estado: 'pendiente',
      detalle: crearDetalleVerificacion('email', 'Te enviaremos un código de 6 dígitos a tu correo.')
    }),
    crearTarjetaSeguridad({
      clave: 'password',
      icono: 'fa-lock',
      titulo: 'Contraseña',
      descripcion: 'Cámbiala de vez en cuando para mantener tu cuenta protegida.',
      textoBoton: 'Cambiar',
      estado: null,
      detalle: crearDetalleContrasena()
    })
  ]);
}

/**
 * Tarjeta desplegable de seguridad.
 * cfg: { clave, icono, titulo, descripcion, textoBoton, estado, detalle }
 *   estado: 'pendiente' | 'ok' | null (sin badge)
 *   deshabilitado: true → el botón no se puede pulsar (función aún no disponible)
 */
function crearTarjetaSeguridad(cfg) {
  const iconoWrap = crearEl('span', { clases: ['config-sec-icon'] }, [
    crearEl('i', { clases: ['fa-solid', cfg.icono] })
  ]);

  const filaTitulo = crearEl('div', { clases: ['config-sec-title-row'] }, [
    crearEl('h6', { clases: ['config-sec-title'], texto: cfg.titulo }),
    cfg.estado ? crearBadgeEstado(cfg.estado) : null
  ]);

  const info = crearEl('div', { clases: ['config-sec-info'] }, [
    filaTitulo,
    crearEl('p', { clases: ['config-sec-desc'], texto: cfg.descripcion })
  ]);
  info.dataset.descripcionOriginal = cfg.descripcion;

  const idDetalle = 'sec-detalle-' + cfg.clave;

  const boton = crearEl('button', {
    clases: ['config-btn-sec'],
    texto: cfg.textoBoton,
    atributos: { type: 'button', 'aria-expanded': 'false', 'aria-controls': idDetalle }
  });
  boton.dataset.textoCerrado = cfg.textoBoton;
  if (cfg.deshabilitado) boton.disabled = true;

  const cabecera = crearEl('div', { clases: ['config-sec-head'] }, [iconoWrap, info, boton]);

  const detalle = crearEl('div', { id: idDetalle, clases: ['config-sec-detail'] }, [
    crearEl('div', { clases: ['config-sec-detail-inner'] }, [cfg.detalle])
  ]);

  const tarjeta = crearEl('section', { clases: ['config-sec-card'] }, [cabecera, detalle]);
  tarjeta.dataset.clave = cfg.clave;
  return tarjeta;
}

function crearBadgeEstado(estado) {
  const esOk = estado === 'ok';

  const icono = crearEl('i', {
    clases: ['fa-solid', esOk ? 'fa-circle-check' : 'fa-circle-exclamation']
  });

  return crearEl('span', {
    clases: ['config-badge', esOk ? 'config-badge--ok' : 'config-badge--pendiente']
  }, [icono, document.createTextNode(esOk ? 'Verificado' : 'Sin verificar')]);
}

/* Contenido desplegable: introducir el código recibido */
function crearDetalleVerificacion(clave, notaTexto) {
  const idInput = 'cfg-codigo-' + clave;

  const label = crearEl('label', { texto: 'Código de verificación', atributos: { for: idInput } });
  const input = crearEl('input', {
    id: idInput,
    clases: ['config-control', 'config-code-input'],
    atributos: {
      type: 'text',
      name: 'codigo_' + clave,
      inputmode: 'numeric',
      maxlength: '6',
      autocomplete: 'one-time-code',
      placeholder: '000000'
    }
  });
  const campo = crearEl('div', { clases: ['config-field'] }, [label, input]);

  const botonConfirmar = crearEl('button', {
    clases: ['config-btn-guardar'],
    atributos: { type: 'button' }
  }, [
    crearEl('i', { clases: ['fa-solid', 'fa-check'] }),
    document.createTextNode(' Confirmar')
  ]);
  botonConfirmar.dataset.accion = 'confirmar';

  const fila = crearEl('div', { clases: ['config-code-row'] }, [campo, botonConfirmar]);

  const botonReenviar = crearEl('button', {
    clases: ['config-link-btn'],
    texto: 'Enviar código',
    atributos: { type: 'button' }
  });
  botonReenviar.dataset.accion = 'enviar';
  botonReenviar.dataset.textoOriginal = 'Enviar código';

  return crearEl('div', { atributos: { 'data-verificacion': clave } }, [
    crearEl('p', { clases: ['config-sec-note'], texto: notaTexto, atributos: { 'data-nota': clave } }),
    fila,
    botonReenviar,
    crearMensajeEstado()
  ]);
}

/* Contenido desplegable: formulario de cambio de contraseña */
function crearDetalleContrasena() {
  const botonActualizar = crearEl('button', {
    clases: ['config-btn-guardar'],
    atributos: { type: 'submit' }
  }, [
    crearEl('i', { clases: ['fa-solid', 'fa-check'] }),
    document.createTextNode(' Actualizar contraseña')
  ]);

  const pista = crearEl('p', {
    clases: ['config-pass-hint'],
    texto: 'Entre 8 y 64 caracteres, con mayúscula, minúscula, número y un carácter especial. Sin espacios.'
  });

  return crearEl('form', { id: 'cfg-form-password', clases: ['config-pass-form'], atributos: { novalidate: '' } }, [
    crearCampoContrasena('cfg-pass-actual', 'contrasena_actual', 'Contraseña actual', 'current-password'),
    crearCampoContrasena('cfg-pass-nueva', 'contrasena_nueva', 'Nueva contraseña', 'new-password'),
    crearCampoContrasena('cfg-pass-repetir', 'contrasena_repetir', 'Repetir nueva contraseña', 'new-password'),
    pista,
    crearMensajeEstado(),
    botonActualizar
  ]);
}

/* Campo de contraseña con botón para mostrar/ocultar.
   Se dibujan los dos iconos (ojo / ojo tachado) y el CSS muestra uno u otro
   según la clase .is-visible: Font Awesome (JS) sustituye cada <i> por un <svg>,
   así que cambiar la clase de un <i> ya renderizado no funcionaría. */
function crearCampoContrasena(id, name, etiquetaTexto, autocomplete) {
  const label = crearEl('label', { texto: etiquetaTexto, atributos: { for: id } });

  const input = crearEl('input', {
    id: id,
    clases: ['config-control', 'config-control--pass'],
    atributos: {
      type: 'password',
      name: name,
      autocomplete: autocomplete,
      placeholder: '••••••••'
    }
  });
  input.dataset.secreto = '1';

  const ojoMostrar = crearEl('span', { clases: ['config-eye-show'] }, [
    crearEl('i', { clases: ['fa-solid', 'fa-eye'] })
  ]);
  const ojoOcultar = crearEl('span', { clases: ['config-eye-hide'] }, [
    crearEl('i', { clases: ['fa-solid', 'fa-eye-slash'] })
  ]);

  const botonOjo = crearEl('button', {
    clases: ['config-eye'],
    atributos: { type: 'button', 'aria-label': 'Mostrar contraseña', 'aria-pressed': 'false' }
  }, [ojoMostrar, ojoOcultar]);
  botonOjo.dataset.objetivo = id;

  const envoltorio = crearEl('div', { clases: ['config-pass-wrap'] }, [input, botonOjo]);

  return crearEl('div', { clases: ['config-field'] }, [label, envoltorio]);
}

/* ── Interacción de pestañas ────────────────────────────── */

function inicializarTabsConfiguracion(modal) {
  const navItems = modal.querySelectorAll('.config-nav-item');
  const panels = modal.querySelectorAll('.config-panel');

  navItems.forEach(function (btn) {
    btn.addEventListener('click', function () {
      cambiarTab(btn, navItems, panels);
    });
  });

  // Al cerrar: vuelve a la primera pestaña y deja Seguridad limpia
  modal.addEventListener('hidden.bs.modal', function () {
    resetearAPrimeraPestaña(navItems, panels);
    reiniciarSeguridad(modal);
  });
}

function cambiarTab(btnSeleccionado, navItems, panels) {
  navItems.forEach(function (b) {
    b.classList.remove('active');
    b.setAttribute('aria-selected', 'false');
  });
  panels.forEach(function (p) { p.classList.remove('active'); });

  btnSeleccionado.classList.add('active');
  btnSeleccionado.setAttribute('aria-selected', 'true');

  const panelObjetivo = document.getElementById('panel-' + btnSeleccionado.dataset.tab);
  if (panelObjetivo) {
    panelObjetivo.classList.add('active');
  }
}

function resetearAPrimeraPestaña(navItems, panels) {
  if (navItems.length === 0) return;

  cambiarTab(navItems[0], navItems, panels);
}

/* ── Interacción de la pestaña Seguridad ────────────────── */

function inicializarSeguridadConfiguracion(modal) {
  const tarjetas = modal.querySelectorAll('.config-sec-card');

  // Desplegar / plegar cada tarjeta (solo una abierta a la vez)
  tarjetas.forEach(function (tarjeta) {
    const boton = tarjeta.querySelector('.config-btn-sec');
    boton.addEventListener('click', function () {
      alternarTarjetaSeguridad(tarjeta, tarjetas);
    });
  });

  // Mostrar / ocultar contraseña
  modal.querySelectorAll('.config-eye').forEach(function (botonOjo) {
    botonOjo.addEventListener('click', function () {
      alternarVisibilidadContrasena(botonOjo);
    });
  });

  // El código de verificación solo admite dígitos (máx. 6)
  modal.querySelectorAll('.config-code-input').forEach(function (input) {
    input.addEventListener('input', function () {
      input.value = input.value.replace(/\D/g, '').slice(0, 6);
    });
  });

  // Los formularios nunca recargan la página: se envían por fetch (ver inicializarFormulariosConfiguracion)
  modal.querySelectorAll('form').forEach(function (form) {
    form.addEventListener('submit', function (e) { e.preventDefault(); });
  });
}

function alternarTarjetaSeguridad(tarjeta, todas) {
  const debeAbrir = !tarjeta.classList.contains('is-open');

  todas.forEach(function (t) { cerrarTarjetaSeguridad(t); });

  if (!debeAbrir) return;

  tarjeta.classList.add('is-open');

  const boton = tarjeta.querySelector('.config-btn-sec');
  boton.setAttribute('aria-expanded', 'true');
  boton.textContent = 'Cerrar';

  tarjeta.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

  const primerInput = tarjeta.querySelector('.config-sec-detail input');
  if (primerInput) primerInput.focus({ preventScroll: true });
}

function cerrarTarjetaSeguridad(tarjeta) {
  tarjeta.classList.remove('is-open');

  const boton = tarjeta.querySelector('.config-btn-sec');
  boton.setAttribute('aria-expanded', 'false');
  boton.textContent = boton.dataset.textoCerrado;
}

function alternarVisibilidadContrasena(botonOjo) {
  const input = document.getElementById(botonOjo.dataset.objetivo);
  if (!input) return;

  const mostrar = input.type === 'password';

  input.type = mostrar ? 'text' : 'password';
  botonOjo.classList.toggle('is-visible', mostrar);
  botonOjo.setAttribute('aria-pressed', mostrar ? 'true' : 'false');
  botonOjo.setAttribute('aria-label', mostrar ? 'Ocultar contraseña' : 'Mostrar contraseña');
}

/* Al cerrar el modal: pliega las tarjetas y borra códigos/contraseñas escritos */
function reiniciarSeguridad(modal) {
  modal.querySelectorAll('.config-sec-card').forEach(function (tarjeta) {
    cerrarTarjetaSeguridad(tarjeta);

    tarjeta.querySelectorAll('input').forEach(function (input) {
      input.value = '';
      if (input.dataset.secreto) input.type = 'password';
    });
  });

  modal.querySelectorAll('.config-eye').forEach(function (botonOjo) {
    botonOjo.classList.remove('is-visible');
    botonOjo.setAttribute('aria-pressed', 'false');
    botonOjo.setAttribute('aria-label', 'Mostrar contraseña');
  });

  modal.querySelectorAll('.config-msg').forEach(function (m) { mostrarMensaje(m, '', 'ok'); });
}

/* ── País y prefijo telefónico ──────────────────────────── */

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

/* ═══════════════════════════════════════════════════════
   CONEXIÓN CON EL SERVIDOR
   ═══════════════════════════════════════════════════════ */

/**
 * Llama a /configuracion/...  Sin `datos` hace un GET; con `datos` hace un POST con el token CSRF de la sesión.
 * Siempre devuelve { ok, mensaje, ... }: los fallos de red o respuestas raras también llegan como { ok: false, mensaje }.
 */
async function cfgPeticion(ruta, datos) {
  const cfg = window.FF_CFG || {};
  const opciones = { credentials: 'same-origin', headers: { 'Accept': 'application/json' } };

  if (datos) {
    opciones.method = 'POST';
    opciones.headers['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
    opciones.headers['X-CSRF-Token'] = cfg.csrf || '';
    opciones.body = new URLSearchParams(datos).toString();
  }

  try {
    const respuesta = await fetch((cfg.base || '') + ruta, opciones);
    let json = null;
    try { json = await respuesta.json(); } catch (e) { json = null; }

    if (json && typeof json.ok === 'boolean') return json;
    return { ok: false, mensaje: 'Respuesta inesperada del servidor. Inténtalo de nuevo.' };
  } catch (e) {
    return { ok: false, mensaje: 'No hay conexión con el servidor. Comprueba tu conexión e inténtalo de nuevo.' };
  }
}

/* Escribe (o borra, si texto está vacío) la línea de estado de un formulario */
function mostrarMensaje(elemento, texto, tipo) {
  if (!elemento) return;
  elemento.textContent = texto || '';
  elemento.classList.remove('config-msg--ok', 'config-msg--error');
  if (texto) elemento.classList.add(tipo === 'ok' ? 'config-msg--ok' : 'config-msg--error');
}

/* Deshabilita un botón mientras dura una petición */
async function conBotonOcupado(boton, tarea) {
  boton.disabled = true;
  boton.setAttribute('aria-busy', 'true');
  try {
    return await tarea();
  } finally {
    // Si la tarea dejó el botón en cuenta atrás (dataset.bloqueado), no se reactiva hasta que termine
    if (!boton.dataset.bloqueado) boton.disabled = false;
    boton.removeAttribute('aria-busy');
  }
}

/* ── Rellenar el formulario con los datos de la cuenta ──── */

async function cargarDatosConfiguracion(modal) {
  const msg = modal.querySelector('#cfg-form-cuenta .config-msg');
  mostrarMensaje(msg, '', 'ok');

  const r = await cfgPeticion('/configuracion/datos');
  if (!r.ok) {
    mostrarMensaje(msg, r.mensaje, 'error');
    return;
  }

  const p = r.perfil;
  modal.querySelector('#cfg-nombre').value = p.nombre || '';
  modal.querySelector('#cfg-apellido').value = p.apellido || '';
  modal.querySelector('#cfg-email').value = p.email || '';
  modal.querySelector('#cfg-telefono').value = p.telefono || '';

  // País y prefijo van sincronizados (el prefijo se elige por país)
  modal.querySelector('#cfg-pais').value = p.pais || '';
  modal.querySelector('#cfg-tel-prefijo').value = p.pais || '';

  const rol = modal.querySelector('#cfg-rol');
  rol.textContent = p.rol || '';
  rol.classList.toggle('config-role--admin', p.rol === 'Administrador' || p.rol === 'Superusuario');

  ponerVerificacion(modal, 'email', !!r.correo_verificado);
}

/* ── Validación en el navegador (el servidor repite todas las comprobaciones) ── */

const CFG_REGEX_NOMBRE = /^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ'\-\s]+$/;
const CFG_REGEX_ESPECIAL = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;

function validarNombreConfig(valor, etiqueta) {
  const v = valor.trim();
  if (v === '') return 'El ' + etiqueta + ' es obligatorio.';
  if (v.length < 2) return etiqueta.charAt(0).toUpperCase() + etiqueta.slice(1) + ': mínimo 2 caracteres.';
  if (v.length > 50) return etiqueta.charAt(0).toUpperCase() + etiqueta.slice(1) + ': máximo 50 caracteres.';
  if (!CFG_REGEX_NOMBRE.test(v)) return 'El ' + etiqueta + ' solo puede contener letras.';
  if (/\s{2,}/.test(v)) return 'El ' + etiqueta + ' no puede contener espacios dobles.';
  return null;
}

function validarCuentaConfig(d) {
  return validarNombreConfig(d.nombre, 'nombre')
    || validarNombreConfig(d.apellido, 'apellido')
    || (!/^\d{9,15}$/.test(d.telefono) ? 'El teléfono debe tener entre 9 y 15 dígitos.' : null)
    || (d.pais === '' ? 'Selecciona un país.' : null);
}

function validarContrasenaConfig(actual, nueva, repetir) {
  if (actual === '') return 'Escribe tu contraseña actual.';
  if (nueva.length < 8) return 'La contraseña nueva debe tener al menos 8 caracteres.';
  if (nueva.length > 64) return 'La contraseña nueva no puede tener más de 64 caracteres.';
  if (!/[A-Z]/.test(nueva)) return 'La contraseña nueva debe contener al menos una mayúscula.';
  if (!/[a-z]/.test(nueva)) return 'La contraseña nueva debe contener al menos una minúscula.';
  if (!/[0-9]/.test(nueva)) return 'La contraseña nueva debe contener al menos un número.';
  if (!CFG_REGEX_ESPECIAL.test(nueva)) return 'La contraseña nueva debe contener al menos un carácter especial (!@#$...).';
  if (/\s/.test(nueva)) return 'La contraseña nueva no puede contener espacios.';
  if (nueva !== repetir) return 'Las dos contraseñas nuevas no coinciden.';
  if (nueva === actual) return 'La contraseña nueva debe ser distinta de la actual.';
  return null;
}

/* ── Formularios y botones de verificación ──────────────── */

function inicializarFormulariosConfiguracion(modal) {
  const valor = function (id) { return modal.querySelector('#' + id).value; };

  // Información de la cuenta
  const formCuenta = modal.querySelector('#cfg-form-cuenta');
  formCuenta.addEventListener('submit', function () {
    const msg = formCuenta.querySelector('.config-msg');
    const boton = formCuenta.querySelector('.config-btn-guardar');

    // Se aceptan espacios y guiones al escribir el teléfono ("600 000 000"): se guardan solo los dígitos
    const inputTelefono = modal.querySelector('#cfg-telefono');
    inputTelefono.value = inputTelefono.value.replace(/[\s-]/g, '');

    const datos = {
      nombre: valor('cfg-nombre').trim(),
      apellido: valor('cfg-apellido').trim(),
      telefono: inputTelefono.value,
      pais: valor('cfg-pais')
    };

    const error = validarCuentaConfig(datos);
    if (error) { mostrarMensaje(msg, error, 'error'); return; }

    conBotonOcupado(boton, async function () {
      const r = await cfgPeticion('/configuracion/guardar', datos);
      mostrarMensaje(msg, r.mensaje, r.ok ? 'ok' : 'error');
      if (r.ok) actualizarNombreEnPagina(r.nombre_completo);
    });
  });

  // Cambio de contraseña
  const formPass = modal.querySelector('#cfg-form-password');
  formPass.addEventListener('submit', function () {
    const msg = formPass.querySelector('.config-msg');
    const boton = formPass.querySelector('.config-btn-guardar');

    const actual = valor('cfg-pass-actual');
    const nueva = valor('cfg-pass-nueva');
    const repetir = valor('cfg-pass-repetir');

    const error = validarContrasenaConfig(actual, nueva, repetir);
    if (error) { mostrarMensaje(msg, error, 'error'); return; }

    conBotonOcupado(boton, async function () {
      const r = await cfgPeticion('/configuracion/contrasena', {
        contrasena_actual: actual,
        contrasena_nueva: nueva,
        contrasena_repetir: repetir
      });

      // Si todo fue bien se vacían los campos; si no, se dejan para que corrija
      if (r.ok) {
        formPass.querySelectorAll('input').forEach(function (i) { i.value = ''; });
      }
      mostrarMensaje(msg, r.mensaje, r.ok ? 'ok' : 'error');
    });
  });

  // Verificación del correo: "Enviar código" y "Confirmar".
  const rutas = { email: '/configuracion/correo' };

  Object.keys(rutas).forEach(function (clave) {
    const detalle = modal.querySelector('[data-verificacion="' + clave + '"]');
    if (!detalle) return;

    const msg = detalle.querySelector('.config-msg');
    const botonEnviar = detalle.querySelector('[data-accion="enviar"]');
    const botonConfirmar = detalle.querySelector('[data-accion="confirmar"]');
    const inputCodigo = detalle.querySelector('.config-code-input');

    botonEnviar.addEventListener('click', function () {
      conBotonOcupado(botonEnviar, async function () {
        const r = await cfgPeticion(rutas[clave] + '/enviar', {});
        mostrarMensaje(msg, r.mensaje, r.ok ? 'ok' : 'error');

        if (r.ok && r.verificado) {
          ponerVerificacion(modal, clave, true);
        } else if (r.ok) {
          inputCodigo.focus();
          iniciarEsperaReenvio(botonEnviar, 60);
        }
      });
    });

    botonConfirmar.addEventListener('click', function () {
      const codigo = inputCodigo.value.trim();
      if (!/^\d{6}$/.test(codigo)) {
        mostrarMensaje(msg, 'Escribe el código de 6 dígitos que te hemos enviado.', 'error');
        return;
      }

      conBotonOcupado(botonConfirmar, async function () {
        const r = await cfgPeticion(rutas[clave] + '/confirmar', { codigo: codigo });
        if (r.ok) {
          ponerVerificacion(modal, clave, true);
        } else {
          mostrarMensaje(msg, r.mensaje, 'error');
        }
      });
    });
  });
}

/* "Enviar código" queda bloqueado unos segundos, con cuenta atrás, para no pedir códigos sin parar */
function iniciarEsperaReenvio(boton, segundos) {
  clearInterval(boton._espera);
  let restantes = segundos;
  boton.dataset.bloqueado = '1';
  boton.disabled = true;
  boton.textContent = 'Reenviar código en ' + restantes + ' s';

  boton._espera = setInterval(function () {
    restantes -= 1;
    if (restantes <= 0) {
      clearInterval(boton._espera);
      delete boton.dataset.bloqueado;
      boton.disabled = false;
      boton.textContent = boton.dataset.textoOriginal;
    } else {
      boton.textContent = 'Reenviar código en ' + restantes + ' s';
    }
  }, 1000);
}

/* Estado de verificación de una tarjeta: insignia, botón y descripción.
   Verificado → insignia verde, sin botón. No verificado → insignia naranja y botón de nuevo visible
   */
function ponerVerificacion(modal, clave, verificado) {
  const tarjeta = modal.querySelector('.config-sec-card[data-clave="' + clave + '"]');
  if (!tarjeta) return;

  const fila = tarjeta.querySelector('.config-sec-title-row');
  const anterior = fila.querySelector('.config-badge');
  const nueva = crearBadgeEstado(verificado ? 'ok' : 'pendiente');
  if (anterior) { fila.replaceChild(nueva, anterior); } else { fila.appendChild(nueva); }

  const desc = tarjeta.querySelector('.config-sec-desc');
  if (verificado) {
    desc.textContent = 'Tu correo está verificado: recibirás avisos y podrás recuperar tu cuenta.';
    cerrarTarjetaSeguridad(tarjeta);
    tarjeta.classList.add('is-verified');
  } else {
    desc.textContent = tarjeta.querySelector('.config-sec-info').dataset.descripcionOriginal || desc.textContent;
    tarjeta.classList.remove('is-verified');
  }
}

/* Tras guardar el nombre, el menú del perfil lo refleja sin recargar */
function actualizarNombreEnPagina(nombreCompleto) {
  if (!nombreCompleto) return;
  document.querySelectorAll('[data-ff-nombre]').forEach(function (el) { el.textContent = nombreCompleto; });
}
