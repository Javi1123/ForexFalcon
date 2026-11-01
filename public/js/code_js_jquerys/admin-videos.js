/* ═══════════════════════════════════════════════════════
   ADMINISTRAR VÍDEOS DE UN SERVICIO (/admin/videos?servicio=XX)

   Subida por TROZOS: PHP limita lo que acepta de una vez (upload_max_filesize,
   a menudo 2 MB). El archivo se corta en trozos del tamaño que el servidor
   admite (cfg.maxTrozo) y se envían uno a uno; el servidor los va guardando y,
   con el último, los une, valida el formato y crea el registro.
   · Un trozo que falla por red se reintenta (hasta 3 veces).
   · Se puede cancelar: se corta la subida y se borran los trozos del servidor.
   Todo texto del servidor se pinta con textContent.
   ═══════════════════════════════════════════════════════ */

(function () {
  'use strict';

  var cfg = window.FF_VIDEOS;
  var form = document.getElementById('formSubida');
  if (!cfg || !form || !window.bootstrap) return;

  var $ = function (id) { return document.getElementById(id); };
  var subida = null;                       // { id, abort, cancelado } mientras hay una subida en marcha

  // ───────────── Utilidades ─────────────
  function aviso(opciones) {
    if (window.Swal) return window.Swal.fire(opciones);
    window.alert(opciones.title || opciones.text || '');
    return Promise.resolve({ isConfirmed: true, value: opciones.inputValue });
  }

  function exito(mensaje) {
    return aviso({ icon: 'success', title: mensaje, timer: 1500, showConfirmButton: false })
      .then(function () { window.location.reload(); });
  }

  function mostrarError(texto) {
    var caja = $('subidaError');
    caja.textContent = texto;
    caja.hidden = false;
    if (caja.scrollIntoView) caja.scrollIntoView({ block: 'nearest' });
  }
  function ocultarError() { var c = $('subidaError'); c.hidden = true; c.textContent = ''; }

  function tamanoLegible(b) {
    if (b >= 1073741824) return (b / 1073741824).toFixed(1).replace('.', ',') + ' GB';
    if (b >= 1048576) return (b / 1048576).toFixed(1).replace('.', ',') + ' MB';
    return Math.max(1, Math.round(b / 1024)) + ' KB';
  }

  function idAleatorio() {
    var bytes = new Uint8Array(16);
    window.crypto.getRandomValues(bytes);
    return Array.prototype.map.call(bytes, function (b) { return ('0' + b.toString(16)).slice(-2); }).join('');
  }

  function extension(nombre) {
    var i = nombre.lastIndexOf('.');
    return i < 0 ? '' : nombre.slice(i + 1).toLowerCase();
  }

  function api(ruta, datos) {
    return fetch(cfg.base + ruta, {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 'Accept': 'application/json', 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-Token': cfg.csrf },
      body: new URLSearchParams(datos).toString()
    }).then(leerRespuesta, function () { throw new Error('Sin conexión con el servidor. Comprueba tu red.'); });
  }

  function leerRespuesta(r) {
    return r.json().catch(function () { return {}; }).then(function (d) {
      if (!r.ok || d.ok === false) {
        var e = new Error(d.error || 'No se pudo completar la operación. Inténtalo de nuevo.');
        e.status = r.status;
        throw e;
      }
      return d;
    });
  }

  // ───────────── Elegir el archivo ─────────────
  function detectarDuracion(archivo) {
    var ayuda = $('vidDuracionAyuda');
    var v = document.createElement('video');
    var url = URL.createObjectURL(archivo);
    v.preload = 'metadata';
    v.onloadedmetadata = function () {
      URL.revokeObjectURL(url);
      if (isFinite(v.duration) && v.duration > 0) {
        $('vidDuracion').value = Math.max(1, Math.round(v.duration));
        ayuda.textContent = 'Detectada automáticamente. Puedes corregirla.';
      } else {
        ayuda.textContent = 'No se pudo leer la duración: escríbela a mano.';
      }
    };
    v.onerror = function () {
      URL.revokeObjectURL(url);
      ayuda.textContent = 'Este navegador no puede leer la duración de este archivo: escríbela a mano.';
    };
    v.src = url;
  }

  $('vidArchivo').addEventListener('change', function () {
    ocultarError();
    $('vidDuracion').value = '';
    var archivo = this.files[0];
    if (!archivo) return;

    if (cfg.extensiones.indexOf(extension(archivo.name)) < 0) {
      mostrarError('Formato no admitido. Sube un vídeo .' + cfg.extensiones.join(', .') + '.');
      this.value = '';
      return;
    }
    if (archivo.size > cfg.maxBytes) {
      mostrarError('El vídeo pesa ' + tamanoLegible(archivo.size) + ' y el máximo es ' + tamanoLegible(cfg.maxBytes) + '.');
      this.value = '';
      return;
    }
    if (!$('vidNombre').value.trim()) {
      $('vidNombre').value = archivo.name.replace(/\.[^.]+$/, '').replace(/[_]+/g, ' ');
    }
    detectarDuracion(archivo);
  });

  // ───────────── Subida por trozos ─────────────
  function enviarTrozo(datos, blob, senal) {
    var fd = new FormData();
    Object.keys(datos).forEach(function (k) { fd.append(k, datos[k]); });
    fd.append('chunk', blob, 'trozo.bin');
    return fetch(cfg.base + '/admin/videos/subir', {
      method: 'POST', credentials: 'same-origin', signal: senal,
      headers: { 'Accept': 'application/json', 'X-CSRF-Token': cfg.csrf },
      body: fd
    }).then(leerRespuesta);
  }

  function espera(ms) { return new Promise(function (ok) { setTimeout(ok, ms); }); }

  /** Un trozo, con hasta 3 reintentos si falla la red o el servidor (los errores de validación no se reintentan). */
  function enviarConReintentos(datos, blob, senal) {
    var intento = 0;
    function probar() {
      intento++;
      return enviarTrozo(datos, blob, senal).catch(function (err) {
        var reintentable = (err.name !== 'AbortError') && (err.name === 'TypeError' || (err.status && err.status >= 500));
        if (!reintentable || intento >= 3 || (subida && subida.cancelado)) throw err;
        return espera(800 * intento).then(probar);
      });
    }
    return probar();
  }

  function progreso(enviados, total) {
    var pct = Math.min(100, Math.round(enviados / total * 100));
    $('subidaBarra').style.width = pct + '%';
    $('subidaBarraCont').setAttribute('aria-valuenow', String(pct));
    $('subidaEstado').textContent = 'Subiendo… ' + pct + '% (' + tamanoLegible(enviados) + ' de ' + tamanoLegible(total) + ')';
  }

  function bloquear(subiendo) {
    $('vidArchivo').disabled = subiendo;
    $('vidNombre').disabled = subiendo;
    $('vidDuracion').disabled = subiendo;
    $('subidaEnviar').disabled = subiendo;
    $('subidaCancelar').hidden = !subiendo;
    $('subidaProgreso').hidden = !subiendo;
  }

  function avisarAlSalir(e) { e.preventDefault(); e.returnValue = ''; }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    if (subida) return;
    ocultarError();

    var archivo = $('vidArchivo').files[0];
    var nombre = $('vidNombre').value.trim();
    var duracion = $('vidDuracion').value.trim();

    if (!archivo) { mostrarError('Elige el archivo de vídeo.'); return; }
    if (!nombre) { mostrarError('El nombre del vídeo es obligatorio.'); $('vidNombre').focus(); return; }
    if (!/^\d+$/.test(duracion) || +duracion < 1) { mostrarError('Indica la duración en segundos.'); $('vidDuracion').focus(); return; }
    if (cfg.maxTrozo < 65536) { mostrarError('El servidor no admite subidas (revisa upload_max_filesize y post_max_size en php.ini).'); return; }

    var tamanoTrozo = cfg.maxTrozo;
    var total = Math.ceil(archivo.size / tamanoTrozo);
    var control = new AbortController();
    subida = { id: idAleatorio(), abort: control, cancelado: false };
    var idSubida = subida.id;

    bloquear(true);
    progreso(0, archivo.size);
    window.addEventListener('beforeunload', avisarAlSalir);

    var comun = { upload_id: idSubida, total: total, servicio: cfg.servicio, nombre: nombre, duracion: duracion, tamano: archivo.size, archivo: archivo.name };

    // Los trozos van EN ORDEN, uno detrás de otro
    var cadena = Promise.resolve();
    for (var i = 0; i < total; i++) {
      (function (indice) {
        cadena = cadena.then(function () {
          if (subida.cancelado) throw new DOMException('cancelado', 'AbortError');
          var datos = Object.assign({ indice: indice }, comun);
          return enviarConReintentos(datos, archivo.slice(indice * tamanoTrozo, (indice + 1) * tamanoTrozo), control.signal)
            .then(function (r) {
              progreso(Math.min(archivo.size, (indice + 1) * tamanoTrozo), archivo.size);
              return r;
            });
        });
      })(i);
    }

    cadena.then(function (r) {
      window.removeEventListener('beforeunload', avisarAlSalir);
      subida = null;
      $('subidaCancelar').hidden = true;      // el último trozo ya llegó: cancelar ya no tiene sentido
      $('subidaEstado').textContent = 'Procesando el vídeo…';
      return exito(r.mensaje || 'Vídeo subido.');
    }).catch(function (err) {
      window.removeEventListener('beforeunload', avisarAlSalir);
      var fueCancelado = subida && subida.cancelado;
      subida = null;
      bloquear(false);
      if (fueCancelado || err.name === 'AbortError') {
        $('vidArchivo').value = '';
        return;
      }
      // Lo subido a medias se descarta del servidor (si no se pudiera, se limpia solo pasadas 24 h)
      api('/admin/videos/cancelar', { upload_id: idSubida }).catch(function () {});
      mostrarError(err.message);
    });
  });

  $('subidaCancelar').addEventListener('click', function () {
    if (!subida) return;
    var id = subida.id;
    subida.cancelado = true;
    subida.abort.abort();
    api('/admin/videos/cancelar', { upload_id: id }).catch(function () {});
    $('subidaEstado').textContent = 'Subida cancelada.';
  });

  // ───────────── Lista: ver / renombrar / eliminar ─────────────
  var modalPrevia = window.bootstrap.Modal.getOrCreateInstance($('modalVistaPrevia'));
  var reproductor = $('vistaPreviaVideo');

  $('modalVistaPrevia').addEventListener('hidden.bs.modal', function () {
    reproductor.pause();
    reproductor.removeAttribute('src');
    reproductor.load();
  });

  function ver(id, nombre) {
    $('vistaPreviaTitulo').textContent = nombre;
    reproductor.src = cfg.base + '/video/ver?id=' + encodeURIComponent(id);
    modalPrevia.show();
  }

  function renombrar(id, nombreActual) {
    aviso({
      title: 'Renombrar vídeo', input: 'text', inputValue: nombreActual,
      inputAttributes: { maxlength: 200, 'aria-label': 'Nuevo nombre' },
      showCancelButton: true, confirmButtonText: 'Guardar', cancelButtonText: 'Cancelar', confirmButtonColor: '#FB8E37',
      inputValidator: function (v) { return (!v || !v.trim()) ? 'El nombre es obligatorio' : null; }
    }).then(function (r) {
      if (!r.isConfirmed) return;
      return api('/admin/videos/renombrar', { id: id, nombre: r.value })
        .then(function (d) { return exito(d.mensaje); })
        .catch(function (err) { aviso({ icon: 'error', title: 'No se pudo renombrar', text: err.message }); });
    });
  }

  function eliminar(id, nombre, boton) {
    aviso({
      icon: 'warning', title: '¿Eliminar «' + nombre + '»?',
      text: 'Se borrará el vídeo y su archivo del servidor. No se puede deshacer.',
      showCancelButton: true, confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar', confirmButtonColor: '#e5484d'
    }).then(function (r) {
      if (!r.isConfirmed) return;
      boton.disabled = true;
      return api('/admin/videos/eliminar', { id: id })
        .then(function (d) { return exito(d.mensaje); })
        .catch(function (err) {
          boton.disabled = false;
          aviso({ icon: 'error', title: 'No se pudo eliminar', text: err.message });
        });
    });
  }

  document.addEventListener('click', function (e) {
    var boton = e.target.closest('.admin-tabla [data-accion]');
    if (!boton) return;
    var fila = boton.closest('tr');
    var id = fila.getAttribute('data-id');
    var nombre = fila.getAttribute('data-nombre');
    switch (boton.getAttribute('data-accion')) {
      case 'ver':       ver(id, nombre); break;
      case 'renombrar': renombrar(id, nombre); break;
      case 'eliminar':  eliminar(id, nombre, boton); break;
    }
  });
})();
