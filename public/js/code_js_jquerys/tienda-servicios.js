/* Modal de información de un servicio (tienda). Solo informa: no tiene precio ni botones de compra. */
document.addEventListener('DOMContentLoaded', function () {
  const modalEl = document.getElementById('modalServicioInfo');
  if (!modalEl) return;

  const modal = new bootstrap.Modal(modalEl);
  // Si falta algún elemento del modal (p. ej. vista y script de versiones distintas), no se rompe nada:
  // se devuelve un elemento "vacío" que absorbe las asignaciones.
  const $ = function (id) { return document.getElementById(id) || document.createElement('span'); };

  // Qué se recibe y cómo se usa, según el tipo de servicio
  const TIPOS = {
    video: {
      recibir: 'Lecciones en vídeo que puedes ver cuando quieras, tantas veces como necesites, desde un reproductor propio con la lista de todos los vídeos del servicio.',
      uso: 'Abre el servicio desde la barra lateral y elige el vídeo que quieras ver.'
    },
    imagenes: {
      recibir: 'Contenido en imágenes (gráficos, capturas y material de apoyo) que consultarás desde la página del servicio.',
      uso: 'Abre el servicio desde la barra lateral para ver su contenido.'
    },
    chat: {
      recibir: 'Atención directa del equipo por chat para resolver tus dudas y orientarte.',
      uso: 'Abre el servicio desde la barra lateral y pulsa el botón para hablar con el equipo.'
    }
  };

  const MODALIDAD = {
    'Pago único': 'Pagas una sola vez y no hay más cobros.',
    'Suscripción mensual': 'Es una suscripción: se renueva cada mes.',
    'Suscripción semanal': 'Es una suscripción: se renueva cada semana.',
    'Suscripción anual': 'Es una suscripción: se renueva cada año.',
    'Suscripción diaria': 'Es una suscripción: se renueva cada día.',
    'Suscripción': 'Es una suscripción que se renueva periódicamente.'
  };

  function chip(texto, icono, extra) {
    const s = document.createElement('span');
    s.className = 'tienda-tag' + (extra ? ' ' + extra : '');
    const i = document.createElement('i');
    i.className = icono;
    s.appendChild(i);
    s.appendChild(document.createTextNode(' ' + texto));
    return s;
  }

  document.querySelectorAll('.btn-info-servicio').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const d = btn.dataset;
      let caracteristicas = [];
      try { caracteristicas = JSON.parse(d.caracteristicas || '[]'); } catch (e) { caracteristicas = []; }
      const tipo = TIPOS[d.tipoClave] || null;

      $('modalServicioTitulo').textContent = d.titulo || '';
      // Font Awesome sustituye cada <i> por un <svg>: para cambiar el icono hay que crear un <i> nuevo
      const cajaIcono = $('modalServicioIcono');
      cajaIcono.innerHTML = '';
      const iconoNuevo = document.createElement('i');
      iconoNuevo.className = d.icono || 'fa-solid fa-star';
      cajaIcono.appendChild(iconoNuevo);
      $('modalServicioDescripcion').textContent = d.descripcion || '';

      // Chips: tipo, modalidad de pago, y "ya contratado"
      const chips = $('modalServicioChips');
      chips.innerHTML = '';
      if (d.tipoEtiqueta) chips.appendChild(chip(d.tipoEtiqueta, d.tipoIcono || 'fa-solid fa-tag'));
      if (d.modalidad) chips.appendChild(chip(d.modalidad, 'fa-solid fa-rotate'));
      if (d.contratado) chips.appendChild(chip('Ya contratado', 'fa-solid fa-circle-check', 'tienda-tag--ok'));
      $('modalServicioAviso').classList.toggle('d-none', !d.contratado);

      // Qué incluye (si el servicio no tiene lista, se explica por su tipo)
      const lista = $('modalServicioCaracteristicas');
      lista.innerHTML = '';
      if (caracteristicas.length) {
        caracteristicas.forEach(function (item) {
          const li = document.createElement('li');
          li.textContent = item;
          lista.appendChild(li);
        });
      } else {
        const li = document.createElement('li');
        li.textContent = tipo ? tipo.recibir.split('.')[0] + '.' : 'Consulta los detalles con nuestro equipo.';
        lista.appendChild(li);
      }

      // Qué recibirás / cómo se usa
      $('modalSeccionRecibir').classList.toggle('d-none', !tipo);
      if (tipo) $('modalServicioRecibir').textContent = tipo.recibir;
      $('modalServicioPasoUso').textContent = tipo ? tipo.uso : 'Abre el servicio desde la barra lateral cuando quieras.';

      modal.show();
    });
  });
});
