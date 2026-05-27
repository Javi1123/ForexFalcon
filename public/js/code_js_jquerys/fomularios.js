"user strict";

///////////////////
// Functions
///////////////////

const seleccionarSecciones = () =>{




}

const validaciones = async () =>{
  const URL = window.location.pathname;

  if(URL.includes("inicio_sesion")){
    // alert(1)
    const formulario = document.querySelector("form");
    const btnEnviarFormulario = document.querySelector("#btnInicioSesion");

    const {validacionesInicioSesion} = await import ("./validaciones.js");

    formulario.addEventListener("submit", (e) => {
      if(validacionesInicioSesion(e)){
        btnEnviarFormulario.textContent = "Comprobando...";
        btnEnviarFormulario.disabled = true;
        formulario.submit();
      }
    });
  }

  if(URL.includes("crear_cuenta")){
    const formulario = document.querySelector("form");
    const btnEnviarFormulario = document.querySelector("#btnRegistrarse");

    const {validacionesFormacion} = await import ("./validaciones.js");

    formulario.addEventListener("submit", (e) => {
      if(validacionesFormacion(e)){
        btnEnviarFormulario.textContent = "Comprobando...";
        btnEnviarFormulario.disabled = true;
        formulario.submit();
      }
    });
  }

}

///////////////////
// Main
///////////////////

const tab_login = document.querySelector("#tab-login-btn");
const tab_registro = document.querySelector("#tab-registro-btn");

// Valicadiones
validaciones();

//TODO VER BIEN ESTO
tab_login.addEventListener("click", e => {
  if(tab_login.classList.contains("active")){
    window.location.href = `./acciones?tipo=inicio`;
  }
})