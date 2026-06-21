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
    // toastCookies();
    return;
  }

  dialogDeDescuento();
};

const dialogDeDescuento = () => {
  const dialog = document.createElement("dialog");
  dialog.setAttribute("style", "padding: 0; border: none; border-radius: 16px; max-width: 460px; width: 90%; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); overflow: hidden; background: #0d0d1a;");

  // Backdrop con blur
  const backdrop = document.createElement("div");
  backdrop.classList.add("modal-backdrop", "fade", "show");
  backdrop.setAttribute("style", "backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); background-color: rgba(0,0,0,0.7);");

  // Contenedor principal
  const modalContent = document.createElement("div");
  modalContent.classList.add("modal-content", "p-4");
  modalContent.setAttribute("style", "background: #0d0d1a; border: 1px solid rgba(255,107,0,0.25); border-radius: 16px; position: relative; overflow: hidden;");

  // Pasarela / ribbon de descuento
  const ribbon = document.createElement("div");
  ribbon.setAttribute("style", "position: absolute; top: 18px; right: -42px; background: #FF6B00; color: #fff; font-weight: 700; font-size: 12px; letter-spacing: 0.5px; text-transform: uppercase; padding: 6px 48px; transform: rotate(45deg); box-shadow: 0 2px 6px rgba(0,0,0,0.3); z-index: 2;");
  ribbon.textContent = "10% dto";

  // Header
  const header = document.createElement("div");
  header.classList.add("modal-header", "border-0", "pb-1");
  header.setAttribute("style", "border-bottom: none;");

  const titulo = document.createElement("h2");
  titulo.classList.add("modal-title", "fw-bold");
  titulo.setAttribute("style", "color: #fff; font-family: inherit; text-transform: uppercase; letter-spacing: 0.5px; font-size: 28px; line-height: 1.1; margin-top: 8px;");
  titulo.textContent = "BIENVENIDO";

  header.append(titulo);

  // Body
  const body = document.createElement("div");
  body.classList.add("modal-body", "pt-2");

  const descripcion = document.createElement("p");
  descripcion.classList.add("mb-3");
  descripcion.setAttribute("style", "color: #B8B8C8; font-size: 14px; line-height: 1.6;");
  descripcion.innerHTML = `Si nos das tu correo corporativo tendrás un <span style="color:#FF6B00; font-weight:600;">10% de descuento</span> en el servicio que quieras.`;

  const label = document.createElement("label");
  label.classList.add("form-label", "fw-semibold");
  label.setAttribute("for", "emailCorporativo");
  label.setAttribute("style", "color: #fff; font-size: 13px;");
  label.textContent = "Correo corporativo";

  const input = document.createElement("input");
  input.classList.add("form-control", "mb-1");
  input.setAttribute("type", "email");
  input.setAttribute("id", "emailCorporativo");
  input.setAttribute("placeholder", "nombre@empresa.com");
  input.setAttribute("autocomplete", "email");
  input.setAttribute("style", "background: #16162a; border: 1px solid #2a2a40; color: #fff; border-radius: 8px; padding: 10px 14px;");

  const feedbackMsg = document.createElement("div");
  feedbackMsg.classList.add("invalid-feedback");
  feedbackMsg.textContent = "Por favor, introduce un correo válido.";

  body.append(descripcion, label, input, feedbackMsg);

  // Footer
  const footer = document.createElement("div");
  footer.classList.add("modal-footer", "border-0", "pt-1", "gap-2");
  footer.setAttribute("style", "border-top: none;");

  const btnCerrar = document.createElement("button");
  btnCerrar.classList.add("btn");
  btnCerrar.setAttribute("style", "background: transparent; color: #B8B8C8; border: 1px solid #2a2a40; border-radius: 8px;");
  btnCerrar.textContent = "Cerrar";

  const btnAceptar = document.createElement("button");
  btnAceptar.classList.add("btn", "fw-bold");
  btnAceptar.setAttribute("style", "background: #FF6B00; color: #fff; border: none; border-radius: 8px;");
  btnAceptar.textContent = "Quiero mi 10% 🎉";

  footer.append(btnCerrar, btnAceptar);

  // Ensamblar
  modalContent.append(ribbon, header, body, footer);
  dialog.append(modalContent);

  // Cerrar
  const cerrarDialog = () => {
    localStorage.setItem("publicidad", fechaDeAhora);
    // toastCookies();
    dialog.close();
    dialog.remove();
    backdrop.remove();
  };

  btnCerrar.addEventListener("click", cerrarDialog);

  btnAceptar.addEventListener("click", () => {
    const emailValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value);
    if (!emailValido) {
      input.classList.add("is-invalid");
      input.style.borderColor = "#E24B4A";
      return;
    }
    input.classList.remove("is-invalid");
    input.style.borderColor = "#8CFF6B";

    console.log("Email enviado:", input.value);
    cerrarDialog();
  });

  input.addEventListener("input", () => {
    input.classList.remove("is-invalid");
    input.style.borderColor = "#2a2a40";
  });

  document.body.append(backdrop, dialog);
  dialog.showModal();
};

// Toast de publicidad y coockies
// const toastCookies = () => {

//   const ultimaVez = localStorage.getItem('toastCookies');
  
//   let pasoLaHora = fechaDeAhora - ultimaVez;

//   if(pasoLaHora < 3600000) return;
  
//   const toastElement = document.querySelector("#toastCookies");
//   if(toastElement){
//     const toast = new bootstrap.Toast(toastElement);
//     toast.show();
//   }

//   const btnCookies = document.querySelector("#btnCookies");

//   btnCookies.addEventListener("click", () =>{
//     localStorage.setItem("toastCookies",fechaDeAhora);
//   })
// }


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