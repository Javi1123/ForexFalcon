/* Administración de usuarios: ficha en un modal, edición de datos, cambio de rol y eliminación.
   Todo el texto que viene del servidor se pinta con textContent (nunca innerHTML). */
(function () {
  'use strict';

  var cfg = window.FF_ADMIN || {};
  var modalEl = document.getElementById('modalUsuario');
  if (!modalEl) return;
  var modal = new bootstrap.Modal(modalEl);
  var $ = function (id) { return document.getElementById(id); };

  var actual = null;   // { usuario, permisos, ... } de la ficha abierta

  var toast = Swal.mixin({ toast: true, position: 'bottom-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });

  function fecha(f) {
    if (!f) return '—';
    var d = new Date(String(f).replace(' ', 'T'));
    if (isNaN(d)) return '—';
    var p = function (n) { return (n < 10 ? '0' : '') + n; };
    return p(d.getDate()) + '/' + p(d.getMonth() + 1) + '/' + d.getFullYear() + ' ' + p(d.getHours()) + ':' + p(d.getMinutes());
  }

  function el(tag, clase, texto) {
    var e = document.createElement(tag);
    if (clase) e.className = clase;
    if (texto !== undefined) e.textContent = texto;
    return e;
  }

  function claseRol(rol) { return rol === 'Superusuario' ? 'adm-rol--super' : (rol === 'Administrador' ? 'adm-rol--admin' : 'adm-rol--user'); }

  function mostrarError(msg) {
    var e = $('usuError');
    e.textContent = msg || '';
    e.classList.toggle('d-none', !msg);
  }

  // ── Llamadas al servidor ──
  function pedirFicha(correo) {
    return fetch(cfg.base + '/admin/usuarios/ficha?correo=' + encodeURIComponent(correo), { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
      .then(function (r) { return r.json().catch(function () { return { ok: false, error: 'Respuesta no válida del servidor.' }; }); });
  }

  function enviar(ruta, datos) {
    var cuerpo = new URLSearchParams();
    Object.keys(datos).forEach(function (k) { cuerpo.append(k, datos[k]); });
    return fetch(cfg.base + ruta, {
      method: 'POST', credentials: 'same-origin',
      headers: { 'X-CSRF-Token': cfg.csrf || '', 'Content-Type': 'application/x-www-form-urlencoded', 'Accept': 'application/json' },
      body: cuerpo.toString()
    }).then(function (r) { return r.json().catch(function () { return { ok: false, error: 'Respuesta no válida del servidor.' }; }); })
      .catch(function () { return { ok: false, error: 'No se pudo conectar con el servidor.' }; });
  }

  // ── Pintar la ficha ──
  function pintar(f) {
    actual = f;
    var u = f.usuario, p = f.permisos;
    mostrarError('');

    var nombre = (u.Nombre + ' ' + u.Apellido).trim();
    $('usuTitulo').textContent = nombre || u.Correo;
    $('usuCorreo').textContent = u.Correo;

    var av = $('usuAvatar');
    av.textContent = (u.Nombre || u.Correo).charAt(0).toUpperCase();
    var filaAvatar = document.querySelector('[data-ficha="' + (window.CSS && CSS.escape ? CSS.escape(u.Correo) : u.Correo) + '"]');
    var pastilla = filaAvatar ? filaAvatar.closest('tr').querySelector('.adm-avatar') : null;
    av.style.background = pastilla ? pastilla.style.background : 'var(--naranja)';

    var chips = $('usuChips');
    chips.innerHTML = '';
    chips.appendChild(el('span', 'adm-rol ' + claseRol(u.Rol), u.Rol));
    var est = el('span', 'adm-estado' + (Number(u.En_linea) === 1 ? ' on' : ''));
    est.appendChild(el('i'));
    est.appendChild(document.createTextNode(' ' + (Number(u.En_linea) === 1 ? 'Sesión abierta' : 'Desconectado')));
    chips.appendChild(est);
    if (p.es_el_mismo) chips.appendChild(el('span', 'adm-rol adm-rol--user', 'Eres tú'));

    // Datos
    var datos = $('usuDatos');
    datos.innerHTML = '';
    [
      ['Teléfono', u.Telefono], ['País', u.Pais], ['Fecha de nacimiento', u.Fecha_nacimiento ? String(u.Fecha_nacimiento).split('-').reverse().join('/') : '—'],
      ['Registro', fecha(u.Fecha_creacion)], ['Último inicio de sesión', fecha(u.Ultimo_login)], ['Último cierre de sesión', fecha(u.Ultimo_logout)]
    ].forEach(function (par) {
      var d = el('div');
      d.appendChild(el('dt', '', par[0]));
      d.appendChild(el('dd', '', par[1] || '—'));
      datos.appendChild(d);
    });

    // Servicios
    var ls = $('usuServicios');
    ls.innerHTML = '';
    if (!f.suscripciones.length) {
      ls.appendChild(el('li', 'vacio', 'Todavía no ha contratado ningún servicio.'));
    } else {
      f.suscripciones.forEach(function (s) {
        var li = el('li');
        li.appendChild(el('strong', '', s.Nombre_servicio));
        li.appendChild(el('span', 'adm-rol ' + (Number(s.Vigente) === 1 ? 'adm-rol--admin' : 'adm-rol--user'), Number(s.Vigente) === 1 ? 'Vigente' : 'Finalizada'));
        li.appendChild(el('span', 'fecha', 'desde ' + fecha(s.Fecha_inicio)));
        ls.appendChild(li);
      });
    }

    // Eventos
    var le = $('usuEventos');
    le.innerHTML = '';
    if (!f.eventos.length) {
      le.appendChild(el('li', 'vacio', 'Sin accesos registrados.'));
    } else {
      f.eventos.forEach(function (e) {
        var inn = e.Tipo_evento === 'login';
        var li = el('li');
        var tag = el('span', 'adm-tag-evento adm-tag-evento--' + (inn ? 'in' : 'out'));
        tag.appendChild(el('i', 'fa-solid ' + (inn ? 'fa-right-to-bracket' : 'fa-right-from-bracket')));
        tag.appendChild(document.createTextNode(' ' + (inn ? 'Inicio' : 'Cierre')));
        li.appendChild(tag);
        li.appendChild(el('span', '', e.Dispositivo + (e.Ip_address ? ' · ' + e.Ip_address : '')));
        li.appendChild(el('span', 'fecha', fecha(e.Fecha_evento)));
        le.appendChild(li);
      });
    }
    $('usuVerActividad').setAttribute('href', cfg.base + '/admin/actividad?q=' + encodeURIComponent(u.Correo));

    // Edición
    $('usuSeccionEditar').classList.toggle('d-none', !p.editar);
    var form = $('usuFormEditar');
    form.nombre.value = u.Nombre; form.apellido.value = u.Apellido; form.telefono.value = u.Telefono; form.pais.value = u.Pais;

    // Rol
    var sel = $('usuRolSelect');
    sel.innerHTML = '';
    p.roles.forEach(function (r) {
      var o = el('option', '', r); o.value = r; if (r === u.Rol) o.selected = true; sel.appendChild(o);
    });
    $('usuSeccionRol').classList.toggle('d-none', !p.roles.length);
    $('usuRolNota').textContent = p.roles.length === 2
      ? 'Como administrador puedes ascender a usuarios a Administrador. Para degradar a un administrador hace falta un Superusuario.' : '';

    // Eliminar
    $('usuSeccionEliminar').classList.toggle('d-none', !p.eliminar);

    // Aviso si no se puede hacer nada más
    var nota = '';
    if (p.es_el_mismo) nota = 'Esta es tu propia cuenta: puedes editar tus datos, pero no cambiarte el rol ni eliminarte.';
    else if (!p.editar && !p.roles.length && !p.eliminar) nota = 'No tienes permisos para modificar a este usuario (solo un Superusuario puede gestionar a otros administradores).';
    $('usuSinPermisos').textContent = nota;
    $('usuSinPermisos').classList.toggle('d-none', !nota);
  }

  function abrir(correo) {
    $('usuTitulo').textContent = 'Cargando…';
    mostrarError('');
    modal.show();
    pedirFicha(correo).then(function (r) {
      if (!r.ok) { mostrarError(r.error || 'No se pudo cargar la ficha.'); return; }
      pintar(r);
    });
  }

  document.querySelectorAll('[data-ficha]').forEach(function (b) {
    b.addEventListener('click', function () { abrir(b.getAttribute('data-ficha')); });
  });

  // ── Acciones ──
  $('usuFormEditar').addEventListener('submit', function (ev) {
    ev.preventDefault();
    if (!actual) return;
    var f = ev.target;
    var boton = f.querySelector('button[type="submit"]');
    boton.disabled = true;
    enviar('/admin/usuarios/editar', {
      correo: actual.usuario.Correo, nombre: f.nombre.value, apellido: f.apellido.value, telefono: f.telefono.value, pais: f.pais.value
    }).then(function (r) {
      boton.disabled = false;
      if (!r.ok) { mostrarError(r.error || 'No se pudo guardar.'); return; }
      toast.fire({ icon: 'success', title: r.mensaje });
      setTimeout(function () { location.reload(); }, 900);
    });
  });

  $('usuBtnRol').addEventListener('click', function () {
    if (!actual) return;
    var nuevo = $('usuRolSelect').value;
    if (nuevo === actual.usuario.Rol) { mostrarError('Ese ya es su rol actual.'); return; }
    Swal.fire({
      icon: 'question', title: 'Cambiar rol',
      text: 'Vas a cambiar el rol de ' + actual.usuario.Correo + ' a «' + nuevo + '».',
      showCancelButton: true, confirmButtonText: 'Cambiar', cancelButtonText: 'Cancelar', confirmButtonColor: '#FB8E37'
    }).then(function (res) {
      if (!res.isConfirmed) return;
      enviar('/admin/usuarios/rol', { correo: actual.usuario.Correo, rol: nuevo }).then(function (r) {
        if (!r.ok) { mostrarError(r.error || 'No se pudo cambiar el rol.'); return; }
        toast.fire({ icon: 'success', title: r.mensaje });
        setTimeout(function () { location.reload(); }, 900);
      });
    });
  });

  $('usuBtnEliminar').addEventListener('click', function () {
    if (!actual) return;
    Swal.fire({
      icon: 'warning', title: '¿Eliminar a este usuario?',
      text: actual.usuario.Correo + ' desaparecerá junto con su historial de accesos y sus chats. No se puede deshacer.',
      showCancelButton: true, confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar', confirmButtonColor: '#e5484d'
    }).then(function (res) {
      if (!res.isConfirmed) return;
      enviar('/admin/usuarios/eliminar', { correo: actual.usuario.Correo }).then(function (r) {
        if (!r.ok) { mostrarError(r.error || 'No se pudo eliminar.'); return; }
        toast.fire({ icon: 'success', title: r.mensaje });
        modal.hide();
        setTimeout(function () { location.reload(); }, 900);
      });
    });
  });
})();
