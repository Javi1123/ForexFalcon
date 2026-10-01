<?php if (isset($_SESSION['toast'])): 
  $toast = $_SESSION['toast'];
  unset($_SESSION['toast']); // Se elimina para que no vuelva a aparecer al refrescar
?>

<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
  <div id="registroToast" class="toast align-items-center text-white <?= $toast['tipo'] === 'error' ? 'bg-danger' : 'bg-success' ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        <i class="fa-solid <?= $toast['tipo'] === 'error' ? 'fa-circle-exclamation' : 'fa-circle-check' ?> me-2"></i>
        <?= htmlspecialchars($toast['mensaje']) ?>
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const toastEl = document.getElementById('registroToast');
    if (toastEl) {
      const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
      toast.show();
    }
  });
</script>
<?php endif; ?>

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