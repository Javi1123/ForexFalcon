document.addEventListener('DOMContentLoaded', function () {
  const modalEl = document.getElementById('modalServicioInfo');
  if (!modalEl) return;

  const modal = new bootstrap.Modal(modalEl);

  const tituloEl = document.getElementById('modalServicioTitulo');
  const descripcionEl = document.getElementById('modalServicioDescripcion');
  const listaEl = document.getElementById('modalServicioCaracteristicas');
  const precioEl = document.getElementById('modalServicioPrecio');
  const btnPagarModal = document.getElementById('modalServicioBtnPagar');

  document.querySelectorAll('.btn-info-servicio').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const titulo = btn.dataset.titulo || '';
      const descripcion = btn.dataset.descripcion || '';
      const precio = btn.dataset.precio || '';
      const urlPago = btn.dataset.urlPago || '#';
      let caracteristicas = [];

      try {
        caracteristicas = JSON.parse(btn.dataset.caracteristicas || '[]');
      } catch (e) {
        caracteristicas = [];
      }

      tituloEl.textContent = titulo;
      descripcionEl.textContent = descripcion;
      precioEl.textContent = precio;

      listaEl.innerHTML = '';
      caracteristicas.forEach(function (item) {
        const li = document.createElement('li');
        li.textContent = item;
        listaEl.appendChild(li);
      });

      btnPagarModal.setAttribute('href', urlPago);

      modal.show();
    });
  });
});
