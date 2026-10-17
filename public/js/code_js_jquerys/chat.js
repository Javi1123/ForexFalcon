/* ═══════════════════════════════════════════════════════
   CHAT — burbuja de mensajería rápida (zona /recursos)
   JS "vanilla": no depende de jQuery.

   Cómo funciona:
   · Los mensajes se guardan SIEMPRE por HTTP (fetch → /chat/*), así que
     no se pierden aunque falle el tiempo real.
   · Pusher solo reparte los avisos en directo (mensaje nuevo, "escribiendo…",
     chat cerrado). Su librería se carga solo cuando hace falta, para no
     abrir una conexión por cada persona que entra a /recursos.
   · Todo texto de usuario se pinta con textContent (nunca innerHTML),
     así un mensaje con <script> o HTML se ve como texto y no se ejecuta.
   ═══════════════════════════════════════════════════════ */

(function () {
  'use strict';

  var cfg = window.FF_CHAT;
  var root = document.getElementById('ffChat');
  if (!cfg || !root) return;

  var $ = function (id) { return document.getElementById(id); };

  var el = {
    launcher: $('ffChatLauncher'), badge: $('ffChatBadge'), panel: $('ffChatPanel'),
    titulo: $('ffChatTitulo'), subtitulo: $('ffChatSubtitulo'),
    volver: $('ffChatVolver'), minimizar: $('ffChatMinimizar'), error: $('ffChatError'),
    opcionAdmin: $('ffOpcionAdmin'), opcionAdminPill: $('ffOpcionAdminPill'), opcionWa: $('ffOpcionWhatsapp'),
    listaPend: $('ffListaPendientes'), listaAct: $('ffListaActivos'),
    cuentaPend: $('ffCuentaPendientes'), cuentaAct: $('ffCuentaActivos'),
    estado: $('ffChatEstado'), estadoTxt: $('ffChatEstadoTxt'),
    mensajes: $('ffChatMensajes'), escribiendo: $('ffChatEscribiendo'),
    cerrado: $('ffChatCerrado'), nueva: $('ffChatNueva'),
    form: $('ffChatForm'), input: $('ffChatInput'), enviar: $('ffChatEnviar'), finalizar: $('ffChatFinalizar')
  };
  var vistas = root.querySelectorAll('[data-vista]');

  // ───────────── Estado ─────────────
  var S = {
    abierto: false,
    vista: 'menu',                 // 'menu' | 'lista' (solo admin) | 'chat'
    pusher: null, conectando: null, lobby: null, errorConexion: false,
    canal: null, canalId: null,
    idChat: null, chatEstado: null, // 'esperando' | 'activo' | 'cerrado'
    contraparte: '', contraparteEnLinea: false,
    pintados: {},                  // ids ya pintados (evita duplicados entre historial, evento y respuesta)
    sinLeer: 0,
    pendientes: [], activos: [],
    retomarAlAbrir: false,         // el usuario tenía un chat abierto al cargar la página
    typingEnviado: 0, typingTimer: null,
    entrada: 0                     // se incrementa al entrar a un chat para descartar respuestas viejas
  };

  // ───────────── Red ─────────────
  function api(ruta, opts) {
    opts = opts || {};
    var init = {
      method: opts.method || 'GET',
      credentials: 'same-origin',
      headers: { 'Accept': 'application/json', 'X-CSRF-Token': cfg.csrf }
    };
    if (opts.body) {
      init.headers['Content-Type'] = 'application/x-www-form-urlencoded';
      init.body = new URLSearchParams(opts.body).toString();
    }
    return fetch(cfg.base + ruta, init).then(function (r) {
      return r.json().catch(function () { return {}; }).then(function (data) {
        if (!r.ok) {
          var e = new Error(data.error || 'No se pudo completar la acción. Inténtalo de nuevo.');
          e.status = r.status;
          throw e;
        }
        return data;
      });
    }, function () {
      throw new Error('Sin conexión con el servidor. Comprueba tu red.');
    });
  }

  function cargarPusher() {
    if (window.Pusher) return Promise.resolve();
    return new Promise(function (ok, ko) {
      var s = document.createElement('script');
      s.src = 'https://js.pusher.com/8.4/pusher.min.js';
      s.onload = ok;
      s.onerror = function () { ko(new Error('No se pudo cargar el servicio de chat en directo.')); };
      document.head.appendChild(s);
    });
  }

  function conectar() {
    if (S.pusher) return Promise.resolve(S.pusher);
    if (!cfg.configurado) return Promise.reject(new Error('El chat en directo todavía no está configurado.'));
    if (!S.conectando) {
      S.conectando = cargarPusher().then(function () {
        S.pusher = new window.Pusher(cfg.pusherKey, {
          cluster: cfg.cluster,
          forceTLS: true,
          channelAuthorization: {
            endpoint: cfg.base + '/chat/pusher_auth',
            headers: { 'X-CSRF-Token': cfg.csrf }
          }
        });
        S.pusher.connection.bind('state_change', function (st) {
          if (st.current === 'unavailable' || st.current === 'failed') {
            S.errorConexion = true;
            mostrarError('Sin conexión en tiempo real. Los mensajes se guardan, pero tendrás que recargar para verlos.');
          } else if (st.current === 'connected' && S.errorConexion) {
            S.errorConexion = false;
            ocultarError();
          }
        });
        return S.pusher;
      }).catch(function (e) {
        S.conectando = null;       // permite reintentar
        throw e;
      });
    }
    return S.conectando;
  }

  function socketId() {
    return (S.pusher && S.pusher.connection && S.pusher.connection.socket_id) || '';
  }

  // ───────────── Utilidades de interfaz ─────────────
  function mostrarError(msg) { el.error.textContent = msg; el.error.hidden = false; }
  function ocultarError() { el.error.hidden = true; el.error.textContent = ''; }

  function confirmar(titulo, texto, boton) {
    if (window.Swal) {
      return window.Swal.fire({
        title: titulo, text: texto, icon: 'question',
        showCancelButton: true, confirmButtonText: boton, cancelButtonText: 'Cancelar',
        confirmButtonColor: '#FB8E37'
      }).then(function (r) { return !!r.isConfirmed; });
    }
    return Promise.resolve(window.confirm(titulo));
  }

  function actualizarBadge() {
    var n = S.sinLeer + (cfg.esAdmin ? S.pendientes.length : 0);
    var abierto = root.classList.contains('is-open');
    el.badge.textContent = n > 99 ? '99+' : String(n);
    el.badge.hidden = n === 0 || abierto;
    root.classList.toggle('has-alert', n > 0);

    if (cfg.esAdmin) {
      el.opcionAdminPill.textContent = String(S.pendientes.length);
      el.opcionAdminPill.hidden = S.pendientes.length === 0;
    }
  }

  function titulo(t, sub) { el.titulo.textContent = t; el.subtitulo.textContent = sub; }

  function mostrarVista(v) {
    S.vista = v;
    Array.prototype.forEach.call(vistas, function (n) { n.hidden = n.getAttribute('data-vista') !== v; });
    el.volver.hidden = (v === 'menu');

    if (v === 'menu') {
      titulo('Mensajería rápida', cfg.esAdmin ? 'Panel de soporte' : '¿En qué podemos ayudarte?');
    } else if (v === 'lista') {
      titulo('Chats de soporte', 'Elige una conversación');
    } else {
      titulo(cfg.esAdmin ? (S.contraparte || 'Usuario') : 'Soporte ForexFalcon',
             cfg.esAdmin ? 'Conversación con el usuario' : 'Te respondemos por aquí');
      S.sinLeer = 0;
      actualizarBadge();
      setTimeout(function () {
        el.mensajes.scrollTop = el.mensajes.scrollHeight;
        if (!el.input.disabled) el.input.focus();
      }, 0);
    }
  }

  function abrir() {
    S.abierto = true;
    el.panel.hidden = false;
    root.classList.add('is-open');
    el.launcher.setAttribute('aria-expanded', 'true');
    el.launcher.setAttribute('aria-label', 'Cerrar mensajería rápida');
    actualizarBadge();

    if (S.retomarAlAbrir && S.idChat) {          // el usuario ya tenía una conversación abierta
      S.retomarAlAbrir = false;
      entrarChat(S.idChat, S.chatEstado, '');
    } else if (S.vista === 'lista') {
      cargarLista();
    } else if (S.vista === 'chat') {
      S.sinLeer = 0;
      actualizarBadge();
    }

    var f = el.panel.querySelector('[data-vista]:not([hidden]) button, [data-vista]:not([hidden]) input:not(:disabled)');
    if (f && S.vista !== 'chat') f.focus();
  }

  function cerrar() {
    S.abierto = false;
    el.panel.hidden = true;
    root.classList.remove('is-open');
    el.launcher.setAttribute('aria-expanded', 'false');
    el.launcher.setAttribute('aria-label', 'Abrir mensajería rápida');
    actualizarBadge();
    el.launcher.focus();
  }

  // ───────────── Mensajes ─────────────
  function pintar(m) {
    if (m.id) {
      if (S.pintados[m.id]) return;
      S.pintados[m.id] = true;
    }
    var mio = m.correo === cfg.correo;

    var caja = document.createElement('div');
    caja.className = 'ff-msg ' + (mio ? 'ff-msg--mio' : 'ff-msg--otro');

    var txt = document.createElement('div');
    txt.textContent = m.mensaje;                 // textContent: el HTML se muestra como texto
    var meta = document.createElement('small');
    meta.className = 'ff-msg__meta';
    var quien = mio ? 'Tú' : (cfg.esAdmin ? (m.nombre || 'Usuario') : 'Soporte');
    meta.textContent = quien + (m.fecha ? ' · ' + m.fecha : '');

    caja.appendChild(txt);
    caja.appendChild(meta);
    el.mensajes.appendChild(caja);
    el.mensajes.scrollTop = el.mensajes.scrollHeight;
  }

  function sistema(texto) {
    var d = document.createElement('div');
    d.className = 'ff-msg ff-msg--sistema';
    d.textContent = texto;
    el.mensajes.appendChild(d);
    el.mensajes.scrollTop = el.mensajes.scrollHeight;
  }

  function recibir(m) {
    pintar(m);
    el.escribiendo.textContent = '';
    if (m.correo !== cfg.correo && !(S.abierto && S.vista === 'chat')) {
      S.sinLeer++;
      actualizarBadge();
    }
  }

  function mostrarEscribiendo() {
    el.escribiendo.textContent = cfg.esAdmin ? 'El usuario está escribiendo…' : 'Soporte está escribiendo…';
    clearTimeout(S.typingTimer);
    S.typingTimer = setTimeout(function () { el.escribiendo.textContent = ''; }, 2500);
  }

  function limpiarConversacion() {
    el.mensajes.textContent = '';
    el.escribiendo.textContent = '';
    S.pintados = {};
    el.cerrado.hidden = true;
    el.form.hidden = false;
    el.finalizar.hidden = false;
  }

  function habilitarEscritura() { el.input.disabled = false; el.enviar.disabled = false; }
  function deshabilitarEscritura() { el.input.disabled = true; el.enviar.disabled = true; }

  function refrescarEstado() {
    var tipo, txt;
    if (S.chatEstado === 'cerrado') {
      tipo = 'cerrado'; txt = 'Conversación cerrada';
    } else if (cfg.esAdmin) {
      if (S.contraparteEnLinea) { tipo = 'ok'; txt = 'Usuario en línea'; }
      else { tipo = 'espera'; txt = 'Usuario desconectado: verá tus mensajes al volver'; }
    } else if (S.chatEstado === 'esperando') {
      tipo = 'espera'; txt = 'Esperando a un administrador… puedes dejar tu mensaje';
    } else if (S.contraparteEnLinea) {
      tipo = 'ok'; txt = 'Conectado con soporte';
    } else {
      tipo = 'espera'; txt = 'Soporte no está en línea ahora; verá tus mensajes cuando vuelva';
    }
    el.estado.setAttribute('data-tipo', tipo);
    el.estadoTxt.textContent = txt;
  }

  function marcarCerrado() {
    S.chatEstado = 'cerrado';
    refrescarEstado();
    deshabilitarEscritura();
    el.form.hidden = true;
    el.finalizar.hidden = true;
    el.nueva.textContent = cfg.esAdmin ? 'Volver a la lista' : 'Iniciar nueva conversación';
    el.cerrado.hidden = false;
    cerrarCanal();
    if (cfg.esAdmin) cargarLista();
  }

  // ───────────── Canal en tiempo real de una conversación ─────────────
  function esContraparte(m) {
    return m && m.id !== cfg.correo && m.info && m.info.rol !== cfg.rol;
  }

  function cerrarCanal() {
    if (S.canal && S.pusher) S.pusher.unsubscribe(S.canal.name);
    S.canal = null;
    S.canalId = null;
    S.contraparteEnLinea = false;
  }

  function abrirCanal(idChat) {
    return conectar().then(function (p) {
      if (S.canal && S.canalId === idChat) return S.canal;
      cerrarCanal();

      var ch = p.subscribe('presence-chat-' + idChat);
      S.canal = ch;
      S.canalId = idChat;

      ch.bind('pusher:subscription_succeeded', function (members) {
        if (S.canal !== ch) return;
        var hay = false;
        members.each(function (m) { if (esContraparte(m)) hay = true; });
        S.contraparteEnLinea = hay;
        refrescarEstado();
        sincronizarHistorial();                  // por si llegó algo entre el historial y la suscripción
      });
      ch.bind('pusher:subscription_error', function () {
        mostrarError('No se pudo abrir el canal en directo. Recarga la página.');
      });
      ch.bind('pusher:member_added', function (m) {
        if (esContraparte(m)) { S.contraparteEnLinea = true; refrescarEstado(); }
      });
      ch.bind('pusher:member_removed', function (m) {
        if (esContraparte(m)) { S.contraparteEnLinea = false; refrescarEstado(); }
      });
      ch.bind('chat-asignado', function () {
        S.chatEstado = 'activo';
        sistema('Un administrador se ha unido a la conversación.');
        refrescarEstado();
      });
      ch.bind('nuevo-mensaje', recibir);
      ch.bind('client-escribiendo', function (d) { if (d && d.rol !== cfg.rol) mostrarEscribiendo(); });
      ch.bind('chat-cerrado', marcarCerrado);

      return ch;
    });
  }

  // ───────────── Historial ─────────────
  function sincronizarHistorial() {
    var id = S.idChat;
    if (!id) return;
    api('/chat/historial?id_chat=' + id).then(function (res) {
      if (S.idChat !== id) return;
      res.mensajes.forEach(pintar);
      if (res.estado === 'cerrado' && S.chatEstado !== 'cerrado') marcarCerrado();
    }).catch(function () { /* silencioso: es un refuerzo */ });
  }

  // ───────────── Entrar a una conversación ─────────────
  function entrarChat(idChat, estadoChat, nombreContraparte) {
    var token = ++S.entrada;
    ocultarError();
    S.idChat = idChat;
    S.chatEstado = estadoChat;
    S.contraparte = nombreContraparte || S.contraparte || '';
    limpiarConversacion();
    deshabilitarEscritura();
    mostrarVista('chat');
    refrescarEstado();

    return api('/chat/historial?id_chat=' + idChat).then(function (res) {
      if (token !== S.entrada) return;
      res.mensajes.forEach(pintar);
      if (res.estado === 'cerrado') { marcarCerrado(); return; }

      S.chatEstado = res.estado;
      refrescarEstado();
      habilitarEscritura();
      if (!el.input.disabled && S.abierto) el.input.focus();
      return abrirCanal(idChat);
    }).catch(function (e) {
      if (token === S.entrada) mostrarError(e.message);
    });
  }

  // ───────────── Usuario ─────────────
  function iniciarUsuario() {
    ocultarError();
    if (!cfg.configurado) {
      mostrarError('El chat en directo todavía no está disponible. Inténtalo más tarde.');
      return;
    }
    if (S.idChat && S.chatEstado !== 'cerrado') {
      entrarChat(S.idChat, S.chatEstado, '');
      return;
    }
    api('/chat/crear', { method: 'POST', body: {} }).then(function (r) {
      return entrarChat(r.id_chat, r.estado, '');
    }).catch(function (e) { mostrarError(e.message); });
  }

  // ───────────── Administrador ─────────────
  function itemLista(c, activo) {
    var b = document.createElement('button');
    b.type = 'button';
    b.className = 'ff-chat__item' + (activo ? ' ff-chat__item--activo' : '');

    var t = document.createElement('span');
    t.className = 'ff-chat__item-txt';
    var n = document.createElement('strong');
    n.textContent = c.nombre || c.correo;
    var m = document.createElement('small');
    m.textContent = c.correo;
    t.appendChild(n);
    t.appendChild(m);

    var h = document.createElement('time');
    h.textContent = c.hora || '';

    b.appendChild(t);
    b.appendChild(h);
    b.addEventListener('click', function () {
      if (activo) entrarChat(c.id_chat, 'activo', c.nombre);
      else atender(c);
    });
    return b;
  }

  function pintarLista() {
    function llenar(cont, lista, activo, vacio) {
      cont.textContent = '';
      if (!lista.length) {
        var v = document.createElement('div');
        v.className = 'ff-chat__vacio';
        v.textContent = vacio;
        cont.appendChild(v);
        return;
      }
      lista.forEach(function (c) { cont.appendChild(itemLista(c, activo)); });
    }
    llenar(el.listaPend, S.pendientes, false, 'Ningún chat en espera');
    llenar(el.listaAct, S.activos, true, 'No tienes conversaciones abiertas');
    el.cuentaPend.textContent = S.pendientes.length;
    el.cuentaAct.textContent = S.activos.length;
  }

  function cargarLista() {
    return api('/chat/pendientes').then(function (r) {
      S.pendientes = r.pendientes || [];
      S.activos = r.activos || [];
      pintarLista();
      actualizarBadge();
    }).catch(function (e) { if (S.abierto) mostrarError(e.message); });
  }

  function atender(c) {
    ocultarError();
    if (!cfg.configurado) {
      mostrarError('Falta configurar Pusher para poder atender chats en directo.');
      return;
    }
    api('/chat/asignar', { method: 'POST', body: { id_chat: c.id_chat } }).then(function () {
      return entrarChat(c.id_chat, 'activo', c.nombre);
    }).catch(function (e) {
      mostrarError(e.message);
      cargarLista();
    });
  }

  function suscribirLobby() {
    if (!cfg.configurado) return;
    conectar().then(function (p) {
      S.lobby = p.subscribe('private-admins-lobby');
      S.lobby.bind('nuevo-chat', cargarLista);
      S.lobby.bind('chat-tomado', cargarLista);
      S.lobby.bind('pusher:subscription_error', function () {
        mostrarError('No se pudo suscribir a los avisos de nuevos chats.');
      });
    }).catch(function () { /* sin tiempo real el admin sigue pudiendo abrir la lista a mano */ });
  }

  // ───────────── Eventos de la interfaz ─────────────
  el.launcher.addEventListener('click', function () { S.abierto ? cerrar() : abrir(); });
  el.minimizar.addEventListener('click', cerrar);

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && S.abierto && !document.querySelector('.swal2-container')) cerrar();
  });

  el.volver.addEventListener('click', function () {
    ocultarError();
    if (S.vista === 'chat') {
      if (cfg.esAdmin) { mostrarVista('lista'); cargarLista(); }
      else mostrarVista('menu');
    } else if (S.vista === 'lista') {
      mostrarVista('menu');
    }
  });

  el.opcionAdmin.addEventListener('click', function () {
    ocultarError();
    if (cfg.esAdmin) { mostrarVista('lista'); cargarLista(); }
    else iniciarUsuario();
  });

  // WhatsApp: si hay número configurado es un enlace normal (wa.me, pestaña nueva) y no se toca.
  // Si no lo hay, la opción sale como "Próximamente" y el click no hace nada.
  // (Para administradores la opción no existe, por eso se comprueba que el elemento exista.)
  if (el.opcionWa) {
    el.opcionWa.addEventListener('click', function (e) {
      if (el.opcionWa.getAttribute('aria-disabled') === 'true') e.preventDefault();
    });
  }

  el.nueva.addEventListener('click', function () {
    if (cfg.esAdmin) { mostrarVista('lista'); cargarLista(); }
    else { S.idChat = null; iniciarUsuario(); }
  });

  el.finalizar.addEventListener('click', function () {
    if (!S.idChat) return;
    var id = S.idChat;
    confirmar('¿Cerrar esta conversación?',
              cfg.esAdmin ? 'El usuario verá que el chat ha terminado.' : 'Podrás abrir otra cuando lo necesites.',
              'Sí, cerrar').then(function (ok) {
      if (!ok) return;
      api('/chat/cerrar', { method: 'POST', body: { id_chat: id } })
        .then(function () { if (S.idChat === id) marcarCerrado(); })
        .catch(function (e) { mostrarError(e.message); });
    });
  });

  el.form.addEventListener('submit', function (e) {
    e.preventDefault();
    var texto = el.input.value.trim();
    if (!texto || !S.idChat || S.chatEstado === 'cerrado') return;

    ocultarError();
    el.enviar.disabled = true;
    api('/chat/mensaje', {
      method: 'POST',
      body: { id_chat: S.idChat, mensaje: texto, socket_id: socketId() }
    }).then(function (res) {
      pintar(res.mensaje);
      el.input.value = '';
    }).catch(function (err) {
      mostrarError(err.message);
      if (err.status === 409) sincronizarHistorial();   // p. ej. el chat se cerró mientras escribías
    }).then(function () {
      if (S.chatEstado !== 'cerrado') { el.enviar.disabled = false; el.input.focus(); }
    });
  });

  // Aviso "escribiendo…" (evento de cliente de Pusher, máx. 1 cada 1,5 s)
  el.input.addEventListener('input', function () {
    var ahora = Date.now();
    if (!S.canal || ahora - S.typingEnviado < 1500) return;
    S.typingEnviado = ahora;
    try { S.canal.trigger('client-escribiendo', { rol: cfg.rol }); } catch (e) { /* aún no suscrito */ }
  });

  // ───────────── Arranque ─────────────
  mostrarVista('menu');

  if (cfg.esAdmin) {
    cargarLista();
    suscribirLobby();
  } else if (cfg.configurado) {
    // ¿Tenía ya una conversación abierta? Si sí, se conecta en silencio para avisar con la burbuja
    // cuando llegue un mensaje, y al abrir el panel se retoma donde se quedó.
    api('/chat/estado').then(function (r) {
      if (!r.chat) return;
      S.idChat = r.chat.id_chat;
      S.chatEstado = r.chat.estado;
      S.retomarAlAbrir = true;
      return abrirCanal(S.idChat);
    }).catch(function () { /* si falla, el usuario puede iniciar el chat a mano */ });
  }
})();
