"user strict";

///////////////////
// Functions
///////////////////

const validaciones = async () => {
  const formularioInicio = document.querySelector('form[action*="tipo=inicio"]')
  const formularioRegistro = document.querySelector('form[action*="tipo=registro"]')

  const btnInicioSesion = document.querySelector("#btnInicioSesion");
  const btnRegistrarse = document.querySelector("#btnRegistrarse");

  const { validacionesInicioSesion, validacionesRegistrarse } = await import("./validaciones.js");

  formularioInicio.addEventListener("submit", (e) => {
    if (validacionesInicioSesion(e)) {
      btnInicioSesion.textContent = "Comprobando...";
      btnInicioSesion.disabled = true;
      btnInicioSesion.classList.add("text-white");
      formularioInicio.submit();
    }
  });

  formularioRegistro.addEventListener("submit", (e) => {
    if (validacionesRegistrarse(e)) {
      btnRegistrarse.textContent = "Comprobando...";
      btnRegistrarse.disabled = true;
      btnRegistrarse.classList.add("text-white");
      formularioRegistro.submit();
    }
  });

};

// Cambio de pestaña en el login / registrarse
const ffClearPanel = (panelId) => {
  const panel = document.querySelector(`#${panelId}`);

  for (const el of panel.querySelectorAll("input, select, textarea")) {
    if (el.type === "checkbox" || el.type === "radio") {
      el.checked = false;
    } else {
      el.value = "";
    }
  }

  for (const el of panel.querySelectorAll(".is-invalid")) {
    el.classList.remove("is-invalid");
  }

  for (const el of panel.querySelectorAll(".invalid-feedback")) {
    el.textContent = "";
    el.classList.add("d-none");
  }
};

const ffShowTab = (targetId) => {
  for (const btn of document.querySelectorAll('.ff-tab-btn')) {
    btn.classList.toggle('ff-active', btn.dataset.ffTarget === targetId);
  }
  for (const panel of document.querySelectorAll('.ff-panel')) {
    panel.classList.toggle('d-none', panel.id !== targetId);
    if (panel.id !== targetId) ffClearPanel(panel.id);
  }
};


// Funcion para limpiar el error cuando se escriba otra vez
const limpiarError = (e) => {
  const campo = e.target;

  // Quitar el borde/estado rojo del input
  campo.classList.remove('is-invalid');

  // Buscar el contenedor (input-group o form-floating) y el div de error justo después
  const contenedor = campo.closest('.input-group') || campo.closest('.form-floating');
  if (!contenedor) return;

  const errorDiv = contenedor.nextElementSibling;
  if (errorDiv && errorDiv.id && errorDiv.id.startsWith('error')) {
    errorDiv.classList.add('d-none');
    errorDiv.textContent = ''; // por si el mensaje se inyecta dinámicamente
  }
}

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('formRegistro');
  if (!form) return;

  const steps = form.querySelectorAll('.ff-step');
  const dots = document.querySelectorAll('.ff-step-dot');

  function goToStep(stepNumber) {
    steps.forEach(step => {
      step.classList.toggle('d-none', step.dataset.step != stepNumber);
    });
    dots.forEach(dot => {
      const dotStep = Number(dot.dataset.step);
      dot.classList.toggle('active', dotStep === stepNumber);
      dot.classList.toggle('done', dotStep < stepNumber);
    });
  }

  // Botones de Atrás y Siguiente en Registro en form
  // Mensajes de error por campo obligatorio
  const MENSAJES_ERROR = {
    regNombre: 'Introduce tu nombre.',
    regApellido: 'Introduce tu apellido.',
    regDate: 'Introduce tu fecha de nacimiento.',
    regEmail: 'Introduce un correo electrónico válido.',
    regTelefono: 'Introduce tu número de teléfono.',
    regPais: 'Selecciona un país.'
  };

  function mostrarErrorCampo(campo) {
    const contenedor = campo.closest('.input-group') || campo.closest('.form-floating');
    if (!contenedor) return;

    const errorDiv = contenedor.nextElementSibling;
    if (errorDiv && errorDiv.id && errorDiv.id.startsWith('error')) {
      errorDiv.textContent = MENSAJES_ERROR[campo.id] || 'Este campo es obligatorio.';
      errorDiv.classList.remove('d-none');
    }
  }

  // Botones de Atrás y Siguiente en Registro en form
  function validarPaso(stepNumber) {
    const step = form.querySelector(`.ff-step[data-step="${stepNumber}"]`);
    const campos = step.querySelectorAll('input[required], select[required]');
    let valido = true;
    let primerInvalido = null;

    campos.forEach(campo => {
      if (!campo.checkValidity()) {
        campo.classList.add('is-invalid');
        mostrarErrorCampo(campo);
        valido = false;
        if (!primerInvalido) primerInvalido = campo;
      } else {
        campo.classList.remove('is-invalid');
      }
    });

    if (primerInvalido) primerInvalido.focus();

    return valido;
  }


  // Botones "Siguiente"
  form.querySelectorAll('.ff-next').forEach(btn => {
    btn.addEventListener('click', () => {
      const pasoActual = btn.closest('.ff-step').dataset.step;
      if (validarPaso(pasoActual)) {
        goToStep(Number(btn.dataset.next));
      }
    });
  });

  // Botones "Atrás"
  form.querySelectorAll('.ff-back').forEach(btn => {
    btn.addEventListener('click', () => {
      goToStep(Number(btn.dataset.back));
    });
  });

   // ── Construir el select de prefijos telefónicos ──────────────────

  const selectPais = document.getElementById('regPais');
  const selectPrefijo = document.getElementById('regTelPrefijo');

  if (selectPais && selectPrefijo) {
    construirSelectPrefijos(selectPais, selectPrefijo);
  }

  async function construirSelectPrefijos(selectPais, selectPrefijo) {
    const { cargarPaises, getFlagEmoji } = await import('./paises.js');
    const datosPaises = await cargarPaises();

    // Limpiar opciones existentes (por si acaso)
    selectPrefijo.innerHTML = '';

    // Añadir opción por defecto
    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = '';
    selectPrefijo.appendChild(defaultOption);

    // Construir opciones con bandera + prefijo (sin nombre del país)
    for (const opt of selectPais.querySelectorAll('option')) {
      if (!opt.value) continue; // saltar el "Selecciona un país"

      const datos = datosPaises[opt.value];
      if (!datos) continue;

      const option = document.createElement('option');
      option.value = opt.value;
      // Mostrar: emoji bandera + prefijo (ej: 🇪🇸 +34)
      const flag = getFlagEmoji(datos.iso);
      option.textContent = `${flag} ${datos.prefijo}`;
      selectPrefijo.appendChild(option);
    }

    // Sincronizar: prefijo → país
    selectPrefijo.addEventListener('change', () => {
      if (selectPrefijo.value) {
        selectPais.value = selectPrefijo.value;
        selectPais.dispatchEvent(new Event('change'));
      }
    });

    // Sincronizar: país → prefijo
    selectPais.addEventListener('change', () => {
      if (selectPais.value) {
        selectPrefijo.value = selectPais.value;
      }
    });

  }  
});

///////////////////
// Main
///////////////////

// Cambio de pestaña en el login / registrarse ( IMPORTANTE NO QUITAR )
for (const btn of document.querySelectorAll('.ff-tab-btn')) {
  btn.addEventListener('click', () => ffShowTab(btn.dataset.ffTarget));
}

// Cambio de ojo para ver las contraseñas
document.querySelector("#eye-contraseña").addEventListener("click", () => {
  const input = document.querySelector("#loginPassword");
  const visible = input.type === "text";
  input.type = visible ? "password" : "text";
  document.querySelector("#eye-contraseña-login").classList.toggle("fa-eye");
  document.querySelector("#eye-contraseña-login").classList.toggle("fa-eye-slash");
});

document.querySelector("#eye-contraseña-registro").addEventListener("click", () => {
  const input = document.querySelector("#regPassword");
  const visible = input.type === "text";
  input.type = visible ? "password" : "text";
  document.querySelector("#eye-icon-registro").classList.toggle("fa-eye");
  document.querySelector("#eye-icon-registro").classList.toggle("fa-eye-slash");
});

document.querySelector("#eye-confirmar-contraseña").addEventListener("click", () => {
  const input = document.querySelector("#regPasswordConfirm");
  const visible = input.type === "text";
  input.type = visible ? "password" : "text";
  document.querySelector("#eye-contraseña-registrarse").classList.toggle("fa-eye");
  document.querySelector("#eye-contraseña-registrarse").classList.toggle("fa-eye-slash");
});



// Quitar el error cuando se escribre otra vez en el campo
const campos = document.querySelectorAll('.form-control, .form-select');

campos.forEach(function (campo) {
  campo.addEventListener('input', limpiarError);
  campo.addEventListener('change', limpiarError); // útil para <select> y fecha
});


// Valicadiones
validaciones();



