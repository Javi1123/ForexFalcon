"user strict";

///////////////////
// Functions
///////////////////

const validaciones = async () =>{
  const formularioInicio = document.querySelector('form[action*="tipo=inicio"]')
  const formularioRegistro = document.querySelector('form[action*="tipo=registro"]')
  
  const btnInicioSesion = document.querySelector("#btnInicioSesion");
  const btnRegistrarse = document.querySelector("#btnRegistrarse");

  const {validacionesInicioSesion,validacionesRegistrarse} = await import ("./validaciones.js");

  formularioInicio.addEventListener("submit", (e) => {
    if(validacionesInicioSesion(e)){
      btnInicioSesion.textContent = "Comprobando...";
      btnInicioSesion.disabled = true;
      btnInicioSesion.classList.add("text-white");
      formularioInicio.submit();
    }
  });

  formularioRegistro.addEventListener("submit", (e) => {
    if(validacionesRegistrarse(e)){
      btnRegistrarse.textContent = "Comprobando...";
      btnRegistrarse.disabled = true;
      btnRegistrarse.classList.add("text-white");
      formularioRegistro.submit();
    }
  });
    
}

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

for (const btn of document.querySelectorAll('.ff-tab-btn')) {
  btn.addEventListener('click', () => ffShowTab(btn.dataset.ffTarget));
}

///////////////////
// Main
///////////////////

// Cambio de ojo para ver las contraseñas
const regPasswordConfirm = document.querySelector("#regPasswordConfirm");
const eyeIconRegistrarse = document.querySelector('#eye-contraseña-registrarse');

document.querySelector("#eye-contraseña").addEventListener("click", () => {
  const input = document.querySelector("#loginPassword");
  const visible = input.type === "text";
  input.type = visible ? "password" : "text";
  document.querySelector("#eye-contraseña-login").classList.toggle("fa-eye");
  document.querySelector("#eye-contraseña-login").classList.toggle("fa-eye-slash");
});

document.querySelector("#eye-confirmar-contraseña").addEventListener("click", () => {
  const input = document.querySelector("#regPasswordConfirm");
  const visible = input.type === "text";
  input.type = visible ? "password" : "text";
  document.querySelector("#eye-contraseña-registrarse").classList.toggle("fa-eye");
  document.querySelector("#eye-contraseña-registrarse").classList.toggle("fa-eye-slash");
});

// Valicadiones
validaciones();