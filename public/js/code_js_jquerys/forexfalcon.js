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

const popPublicidad = () => {
  const yaAbierto = document.querySelector("dialog[open]");

  const ultimaVez = localStorage.getItem("publicidad"); // getItem, no .publicidad
  const pasoLaHora = fechaDeAhora - Number(ultimaVez);  // Number() evita NaN

  if (yaAbierto || (ultimaVez && pasoLaHora < 1800000)) {
    toastCookies();
    return;
  }

  dialogDeDescuento();
};

const dialogDeDescuento = () => {
  const dialog = document.createElement("dialog");
  dialog.setAttribute("style", "padding: 0; border: none; border-radius: 12px; max-width: 460px; width: 90%; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);");

  // Backdrop con blur
  const backdrop = document.createElement("div");
  backdrop.classList.add("modal-backdrop", "fade", "show");
  backdrop.setAttribute("style", "backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);");

  // Contenedor principal
  const modalContent = document.createElement("div");
  modalContent.classList.add("modal-content", "p-4");

  // Header
  const header = document.createElement("div");
  header.classList.add("modal-header", "border-0", "pb-1");

  const titulo = document.createElement("h2");
  titulo.classList.add("modal-title", "fw-bold", "fs-4");
  titulo.textContent = "BIENVENIDO";

  header.append(titulo);

  // Body
  const body = document.createElement("div");
  body.classList.add("modal-body", "pt-2");

  const descripcion = document.createElement("p");
  descripcion.classList.add("text-muted", "mb-3");
  descripcion.textContent = "Si nos das tu correo corporativo tendrás un 10% de descuento en el servicio que quieras.";

  const label = document.createElement("label");
  label.classList.add("form-label", "fw-semibold");
  label.setAttribute("for", "emailCorporativo");
  label.textContent = "Correo corporativo";

  const input = document.createElement("input");
  input.classList.add("form-control", "mb-1");
  input.setAttribute("type", "email");
  input.setAttribute("id", "emailCorporativo");
  input.setAttribute("placeholder", "nombre@empresa.com");
  input.setAttribute("autocomplete", "email");

  const feedbackMsg = document.createElement("div");
  feedbackMsg.classList.add("invalid-feedback");
  feedbackMsg.textContent = "Por favor, introduce un correo válido.";

  body.append(descripcion, label, input, feedbackMsg);

  // Footer
  const footer = document.createElement("div");
  footer.classList.add("modal-footer", "border-0", "pt-1", "gap-2");

  const btnCerrar = document.createElement("button");
  btnCerrar.classList.add("btn", "btn-outline-secondary");
  btnCerrar.textContent = "Cerrar";

  const btnAceptar = document.createElement("button");
  btnAceptar.classList.add("btn", "btn-primary");
  btnAceptar.textContent = "Quiero mi 10% 🎉";

  footer.append(btnCerrar, btnAceptar);

  // Ensamblar
  modalContent.append(header, body, footer);
  dialog.append(modalContent);

  // Cerrar
  const cerrarDialog = () => {
    localStorage.setItem("publicidad", fechaDeAhora);
    toastCookies();
    dialog.close();
    dialog.remove();
    backdrop.remove();
  };

  btnCerrar.addEventListener("click", cerrarDialog);

  btnAceptar.addEventListener("click", () => {
    const emailValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value);
    if (!emailValido) {
      input.classList.add("is-invalid");
      return;
    }
    input.classList.remove("is-invalid");
    input.classList.add("is-valid");

    // Aquí va tu lógica con el email: input.value
    console.log("Email enviado:", input.value);
    cerrarDialog();
  });

  input.addEventListener("input", () => {
    input.classList.remove("is-invalid");
  });

  document.body.append(backdrop, dialog);
  dialog.showModal();
};

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

///////////////////
// Main
///////////////////

const fechaDeAhora = new Date().getTime();

// Publicidad y coockies
popPublicidad();

// Textarea de los formularios
tinymceInitial();

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