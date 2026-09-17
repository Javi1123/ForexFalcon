/* ═══════════════════════════════════════════════════════
   CONFIGURACIÓN — Interacción del modal
   Cambio de pestañas (Información de la cuenta / Seguridad)
   ═══════════════════════════════════════════════════════ */

document.addEventListener('DOMContentLoaded', function () {
  // Abrir modal de configuración manualmente (evita el auto-init roto)
  const btnConfig = document.getElementById('btnAbrirConfiguracion');
  const modalConfigEl = document.getElementById('modalConfiguracion');

  if (btnConfig && modalConfigEl) {
    btnConfig.addEventListener('click', function (e) {
      e.preventDefault();
      const m = bootstrap.Modal.getOrCreateInstance(modalConfigEl);
      m.show();
    });
  }

  initConfiguracion();
});

function initConfiguracion() {
  const modal = document.getElementById('modalConfiguracion');
  if (!modal) return; // esta vista no tiene el modal, no hacemos nada

  const navItems = modal.querySelectorAll('.config-nav-item');
  const panels = modal.querySelectorAll('.config-panel');

  navItems.forEach(function (btn) {
    btn.addEventListener('click', function () {
      cambiarTab(btn, navItems, panels);
    });
  });

  // Al cerrarse el modal, siempre volvemos a la primera pestaña
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