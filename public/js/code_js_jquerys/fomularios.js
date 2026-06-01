"user strict";

///////////////////
// Functions
///////////////////

const validaciones = async () =>{
  const formularioInicio = document.querySelector('form[action*="tipo=inicio"]')
  const formularioRegistro = document.querySelector('form[action*="tipo=registro"]')
  
  const btnInicioSesion = document.querySelector("#btnInicioSesion");
  const btnRegistrarse = document.querySelector("#btnRegistrarse");

  const {validacionesInicioSesion,validacionesFormacion} = await import ("./validaciones.js");

  formularioInicio.addEventListener("submit", (e) => {
    console.log(validacionesInicioSesion(e));
    if(validacionesInicioSesion(e)){
      btnInicioSesion.textContent = "Comprobando...";
      btnInicioSesion.disabled = true;
      btnInicioSesion.classList.add("text-white");
      formularioInicio.submit();
    }
  });


  formularioRegistro.addEventListener("click", (e) => {
    if(validacionesFormacion(e)){
      btnRegistrarse.textContent = "Comprobando...";
      btnRegistrarse.disabled = true;
      btnRegistrarse.classList.add("text-white");
      formularioRegistro.submit();
    }
  });
}

const ffShowTab = (targetId) =>{
  document.querySelectorAll('.ff-tab-btn').forEach(btn => {
    btn.classList.toggle('ff-active', btn.dataset.ffTarget === targetId);
  });
  document.querySelectorAll('.ff-panel').forEach(panel => {
    panel.classList.toggle('d-none', panel.id !== targetId);
  });
}


///////////////////
// Main
///////////////////

document.querySelectorAll('.ff-tab-btn').forEach(btn => {
  btn.addEventListener('click', () => ffShowTab(btn.dataset.ffTarget));
});

const tab_login = document.querySelector("#tab-login-btn");
const tab_registro = document.querySelector("#tab-registro-btn");

// Valicadiones
validaciones();