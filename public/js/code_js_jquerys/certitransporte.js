"user strict";

///////////////////
// Functions
///////////////////

// Animacione de los elementos
const animationSeccions = () =>{
  const elementos = document.querySelectorAll('.reveal');

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.15,
    rootMargin: '0px 0px -50px 0px'
  });
  
  // Observar cada elemento
  elementos.forEach(elemento => {
    observer.observe(elemento);
  });
}

const popPublicidad = () =>{

  const publicidad = document.querySelector(".swal2-popup");
  
  let pasoLaHora = fechaDeAhora - localStorage.publicidad;

  if(publicidad || pasoLaHora < 1800000){
    toastCookies();
    return;
  } 
  
  Swal.fire({
    background: "rgba(0, 0, 0, 0.6)",
    imageUrl: "public/imagenes/convocatoria_2026.jpeg",
    imageWidth: 400,
    imageHeight: 500,
    imageAlt: "Convocatoria   del 2026 de forexfalcon.",
    confirmButtonText: "De acuerdo",
    confirmButtonColor: "#FF6B00",
  }).then( () => {
    localStorage.setItem("publicidad",fechaDeAhora);
    toastCookies();
  });
  
}

// Toast de publicidad y coockies
const toastCookies = () => {

  const ultimaVez = localStorage.getItem('toastCookies');
  
  let pasoLaHora = fechaDeAhora - ultimaVez;

  if(pasoLaHora < 3600000) return;
  
  const toastElement = document.querySelector("#toastCookies");
  if(toastElement){
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
  }

  const btnCookies = document.querySelector("#btnCookies");

  btnCookies.addEventListener("click", () =>{
    localStorage.setItem("toastCookies",fechaDeAhora);
  })
}


// Textarea con formato HTML
const tinymceInitial = () =>{
  // Función genérica para evitar repetir el mismo código de "setup" en ambos
  const commonSetup = function (editor) {
    editor.on('change', function () {
      editor.save(); // Sincroniza con el textarea automáticamente
    });

    editor.on('init', function () {
      editor.getContainer().style.transition = "border-color 0.2s";
    });

    editor.on('blur', function () {
      editor.getContainer().style.borderColor = "#d1d5db"; // gray-300
    });
  };

  // Configuración común para reducir código
  const baseConfig = {
    license_key: 'gpl',
    language: 'es',
    plugins: 'lists link autolink charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
    toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat',
    skin: 'oxide',
    content_css: 'default',
    height: 300,
    menubar: false,
    promotion: false,
    branding: false,
    setup: commonSetup
  };

  // Inicializar primer editor
  tinymce.init({
    ...baseConfig,
    selector: '#interes'
  });

  tinymce.init({
    ...baseConfig,
    selector: '#sugerencia'
  });

  const form = document.querySelector('form');
  if (form) {
    form.addEventListener('submit', function() {
      tinymce.triggerSave(); 
    });
  }
}


// Llamada dinamiaca a las validaciones para los formularios
const validaciones = async () =>{
  const URL = window.location.pathname;

  if(URL.includes("sugerencias")){
    const formulario = document.querySelector("#formulario");
    const btnBorrarFormulario = document.querySelector(".btnBorrarFormulario");
    const btnEnviarFormulario = document.querySelector(".btnEnviarFormulario");

    const {validacionesSugerencias} = await import ("./validaciones.js");

    formulario.addEventListener("submit", (e) => {
      btnEnviarFormulario.textContent = "Comprobando...";
      btnEnviarFormulario.disabled = true;
      btnBorrarFormulario.remove();
      validacionesSugerencias(e);
    });
  }

  if(URL.includes("registrarse")){
    const formulario = document.querySelector("#formulario");
    const btnEnviarFormulario = document.querySelector(".btnEnviarFormulario");
    const btnBorrarFormulario = document.querySelector(".btnBorrarFormulario");

    const {validacionesFormacion} = await import ("./validaciones.js");

    formulario.addEventListener("submit", (e) => {
      btnEnviarFormulario.textContent = "Comprobando...";
      btnEnviarFormulario.disabled = true;
      btnBorrarFormulario.remove();
      validacionesFormacion(e);
    });
  }

}

///////////////////
// Main
///////////////////

const fechaDeAhora = new Date().getTime();

// Publicidad y coockies
popPublicidad();

// Textarea de los formularios
tinymceInitial();

// Valicadiones
validaciones();

// Animar secciones
animationSeccions();

///////////////////
// Jquery
///////////////////

// Tooltip
$(document).ready(function() {
  $('[data-bs-toggle="tooltip"]').each(function(index, element) {
    new bootstrap.Tooltip(element, {
      fallbackPlacements: []
    });
  });
});