/* ═══════════════════════════════════════════════════════
   ADMINISTRAR SERVICIOS (/admin)
   JS "vanilla". Cada botón llama a una acción JSON del servidor,
   que cambia Stripe y la base de datos a la vez. Al terminar bien,
   se recarga la página para ver las tarjetas actualizadas.
   Todo texto del servidor se pinta con textContent / value.
   ═══════════════════════════════════════════════════════ */

(function () {
  'use strict';

  var cfg = window.FF_ADMIN;
  var elServicio = document.getElementById('modalServicio');
  var elPrecio = document.getElementById('modalPrecio');
  if (!cfg || !elServicio || !elPrecio || !window.bootstrap) return;

  var $ = function (id) { return document.getElementById(id); };
  var modalServicio = window.bootstrap.Modal.getOrCreateInstance(elServicio);
  var modalPrecio = window.bootstrap.Modal.getOrCreateInstance(elPrecio);

  var estado = { modo: 'crear', producto: null, codigoTocado: false };

  // ───────────── Red ─────────────
  function api(ruta, datos) {
    return fetch(cfg.base + ruta, {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-CSRF-Token': cfg.csrf
      },
      body: new URLSearchParams(datos).toString()
    }).then(function (r) {
      return r.json().catch(function () { return {}; }).then(function (d) {
        if (!r.ok || d.ok === false) {
          var e = new Error(d.error || 'No se pudo completar la operación. Inténtalo de nuevo.');
          e.status = r.status;
          throw e;
        }
        return d;
      });
    }, function () {
      throw new Error('Sin conexión con el servidor. Comprueba tu red.');
    });
  }

  function aviso(opciones) {
    if (window.Swal) return window.Swal.fire(opciones);
    window.alert(opciones.title || opciones.text || '');
    return Promise.resolve({ isConfirmed: true });
  }

  function exito(mensaje) {
    return aviso({ icon: 'success', title: mensaje, timer: 1600, showConfirmButton: false })
      .then(function () { window.location.reload(); });
  }

  function mostrarError(idCaja, texto) {
    var caja = $(idCaja);
    caja.textContent = texto;
    caja.hidden = false;
    caja.scrollIntoView({ block: 'nearest' });
  }
  function ocultarError(idCaja) { var c = $(idCaja); c.hidden = true; c.textContent = ''; }

  function bloquear(boton, textoEspera) {
    var original = boton.textContent;
    boton.disabled = true;
    boton.textContent = textoEspera;
    return function () { boton.disabled = false; boton.textContent = original; };
  }

  function datosTarjeta(boton) {
    return JSON.parse(boton.closest('.servicio-card').getAttribute('data-servicio'));
  }

  // ───────────── Icono: vista previa ─────────────
  function pintarIcono(clase) {
    var cont = $('svcIconoPrev');
    cont.textContent = '';
    var i = document.createElement('i');
    i.className = clase;                       // la clase sale de un <select>, no de texto libre
    cont.appendChild(i);
  }

  function elegirIcono(clase) {
    var select = $('svcIcono');
    var existe = Array.prototype.some.call(select.options, function (o) { return o.value === clase; });
    if (!existe) {                             // producto con un icono que no está en la lista: se conserva
      var op = document.createElement('option');
      op.value = clase;
      op.textContent = 'Actual (' + clase.replace(/^fa-\w+ fa-/, '') + ')';
      select.insertBefore(op, select.firstChild);
    }
    select.value = clase;
    pintarIcono(clase);
  }

  $('svcIcono').addEventListener('change', function () { pintarIcono(this.value); });

  // ───────────── Contadores ─────────────
  function contarCaracteristicas() {
    var n = $('svcCaracteristicas').value.split(/\r?\n/).filter(function (l) { return l.trim() !== ''; }).length;
    $('svcCaractContador').textContent = n;
    $('svcCaractContador').style.color = n > 15 ? '#b3262b' : '';
  }
  $('svcCaracteristicas').addEventListener('input', contarCaracteristicas);
  $('svcLarga').addEventListener('input', function () { $('svcLargaContador').textContent = this.value.length; });

  // ───────────── Sugerir código a partir del nombre (solo al crear) ─────────────
  function sugerirCodigo(nombre) {
    var limpio = nombre.toLowerCase().replace(/[áàä]/g, 'a').replace(/[éèë]/g, 'e').replace(/[íìï]/g, 'i')
      .replace(/[óòö]/g, 'o').replace(/[úùü]/g, 'u').replace(/ñ/g, 'n');
    var iniciales = limpio.split(/[^a-z0-9]+/).filter(Boolean).map(function (p) { return p[0]; }).join('');
    return iniciales.toUpperCase().slice(0, 4);
  }
  $('svcNombre').addEventListener('input', function () {
    if (estado.modo === 'crear' && !estado.codigoTocado) $('svcCodigo').value = sugerirCodigo(this.value);
  });
  $('svcCodigo').addEventListener('input', function () { estado.codigoTocado = true; });

  // ───────────── Abrir formularios ─────────────
  function abrirCrear() {
    estado = { modo: 'crear', producto: null, codigoTocado: false };
    $('formServicio').reset();
    ocultarError('svcError');
    $('svcTitulo').textContent = 'Nuevo servicio';
    $('svcCodigo').readOnly = false;
    $('svcCodigoAyuda').textContent = 'Letras y números. No se puede cambiar después. Si lo dejas vacío se genera solo.';
    $('svcGrupoPrecio').hidden = false;
    $('svcGuardar').textContent = 'Crear servicio';
    elegirIcono('fa-solid fa-star');
    $('svcLargaContador').textContent = '0';
    contarCaracteristicas();
    modalServicio.show();
    setTimeout(function () { $('svcNombre').focus(); }, 300);
  }

  function abrirEditar(s) {
    estado = { modo: 'editar', producto: s, codigoTocado: true };
    $('formServicio').reset();
    ocultarError('svcError');
    $('svcTitulo').textContent = 'Editar servicio';
    $('svcNombre').value = s.nombre;
    $('svcCodigo').value = s.id_servicio;
    $('svcCodigo').readOnly = true;
    $('svcCodigoAyuda').textContent = 'El código no se puede cambiar: las suscripciones existentes dependen de él.';
    $('svcCorta').value = s.descripcion_corta;
    $('svcLarga').value = s.descripcion_larga;
    $('svcLargaContador').textContent = s.descripcion_larga.length;
    $('svcCaracteristicas').value = s.caracteristicas.join('\n');
    $('svcGrupoPrecio').hidden = true;           // el precio se cambia con su propio botón
    $('svcGuardar').textContent = 'Guardar cambios';
    elegirIcono(s.icono);
    contarCaracteristicas();
    modalServicio.show();
    setTimeout(function () { $('svcNombre').focus(); }, 300);
  }

  function abrirPrecio(s) {
    estado.producto = s;
    $('formPrecio').reset();
    ocultarError('prcError');
    $('prcServicio').textContent = s.nombre;
    $('prcActual').textContent = s.precio + ' ' + s.periodo;
    $('prcImporte').value = s.importe;
    var tipo = $('prcTipo');
    if (Array.prototype.some.call(tipo.options, function (o) { return o.value === s.tipo; })) tipo.value = s.tipo;
    modalPrecio.show();
    setTimeout(function () { $('prcImporte').focus(); $('prcImporte').select(); }, 300);
  }

  // ───────────── Enviar formularios ─────────────
  $('formServicio').addEventListener('submit', function (e) {
    e.preventDefault();
    ocultarError('svcError');

    var nombre = $('svcNombre').value.trim();
    if (!nombre) { mostrarError('svcError', 'El nombre es obligatorio.'); $('svcNombre').focus(); return; }

    var datos = {
      nombre: nombre,
      descripcion_corta: $('svcCorta').value,
      descripcion_larga: $('svcLarga').value,
      icono: $('svcIcono').value,
      caracteristicas: $('svcCaracteristicas').value
    };

    var ruta;
    if (estado.modo === 'crear') {
      if (!$('svcImporte').value.trim()) { mostrarError('svcError', 'Indica el precio.'); $('svcImporte').focus(); return; }
      datos.id_servicio = $('svcCodigo').value.trim();
      datos.importe = $('svcImporte').value;
      datos.tipo = $('svcTipo').value;
      ruta = '/admin/crear';
    } else {
      datos.product_id = estado.producto.product_id;
      ruta = '/admin/editar';
    }

    var desbloquear = bloquear($('svcGuardar'), 'Guardando…');
    api(ruta, datos).then(function (r) {
      modalServicio.hide();
      return exito(r.mensaje);
    }).catch(function (err) {
      desbloquear();
      mostrarError('svcError', err.message);
    });
  });

  $('formPrecio').addEventListener('submit', function (e) {
    e.preventDefault();
    ocultarError('prcError');
    if (!$('prcImporte').value.trim()) { mostrarError('prcError', 'Indica el nuevo precio.'); $('prcImporte').focus(); return; }

    var desbloquear = bloquear($('prcGuardar'), 'Cambiando…');
    api('/admin/precio', {
      product_id: estado.producto.product_id,
      importe: $('prcImporte').value,
      tipo: $('prcTipo').value
    }).then(function (r) {
      modalPrecio.hide();
      return exito(r.mensaje);
    }).catch(function (err) {
      desbloquear();
      mostrarError('prcError', err.message);
    });
  });

  // ───────────── Archivar / restaurar / sincronizar ─────────────
  function archivar(s, boton) {
    aviso({
      icon: 'warning',
      title: '¿Archivar «' + s.nombre + '»?',
      text: 'Dejará de aparecer en la tienda. Quien ya esté suscrito no se ve afectado y podrás restaurarlo cuando quieras.',
      showCancelButton: true,
      confirmButtonText: 'Sí, archivar',
      cancelButtonText: 'Cancelar',
      confirmButtonColor: '#e5484d'
    }).then(function (r) {
      if (!r.isConfirmed) return;
      return cambiarEstado(s, '0', boton);
    });
  }

  function cambiarEstado(s, activo, boton) {
    boton.disabled = true;
    return api('/admin/estado', { product_id: s.product_id, activo: activo })
      .then(function (r) { return exito(r.mensaje); })
      .catch(function (err) {
        boton.disabled = false;
        aviso({ icon: 'error', title: 'No se pudo completar', text: err.message });
      });
  }

  function sincronizar(s, boton) {
    boton.disabled = true;
    return api('/admin/sincronizar', { product_id: s.product_id })
      .then(function (r) { return exito(r.mensaje); })
      .catch(function (err) {
        boton.disabled = false;
        aviso({ icon: 'error', title: 'No se pudo sincronizar', text: err.message });
      });
  }

  // ───────────── Eventos ─────────────
  $('btnNuevoServicio').addEventListener('click', abrirCrear);

  document.addEventListener('click', function (e) {
    var boton = e.target.closest('.servicio-card--admin [data-accion]');
    if (!boton) return;

    var s = datosTarjeta(boton);
    switch (boton.getAttribute('data-accion')) {
      case 'editar':       abrirEditar(s); break;
      case 'precio':       abrirPrecio(s); break;
      case 'archivar':     archivar(s, boton); break;
      case 'restaurar':    cambiarEstado(s, '1', boton); break;
      case 'sincronizar':  sincronizar(s, boton); break;
    }
  });
})();
