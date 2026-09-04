<footer class="app-footer">
  <strong>
    © 2026 ForexFalcon · Todos los derechos reservados
    <a href="https://forexfalcon.com" class="text-decoration-none">forexfalcon.com</a>.
  </strong>
</footer>

<script>
  /**
 * recursos.js
 * Lógica de la Tienda: al pulsar el botón de información de una card de
 * servicio, se rellena y se muestra un toast (Bootstrap 5, incluido con
 * AdminLTE 4) con el detalle completo de ese servicio.
 */
document.addEventListener("DOMContentLoaded", function () {
  const toastEl = document.getElementById("toastInfoServicio");
  if (!toastEl) return;

  const toastTitulo = document.getElementById("toastInfoServicioTitulo");
  const toastBody = document.getElementById("toastInfoServicioBody");
  const toast = new bootstrap.Toast(toastEl, { autohide: false });

  document.querySelectorAll(".servicio-card__info-btn").forEach(function (btn) {
    btn.addEventListener("click", function () {
      const nombre = btn.getAttribute("data-nombre") || "Servicio";
      const info = btn.getAttribute("data-info") || "";

      toastTitulo.textContent = nombre;
      toastBody.textContent = info;

      toast.show();
    });
  });
});

</script>