"user strict";

///////////////////
// Functions
///////////////////

// Funcion de editar para los Alumno
const editarAlumno = (e) =>{

  if(e.target.tagName != "BUTTOM" && !e.target.classList.contains("btnEditarCurso") && !e.target.classList.contains("bi-pen-fill")) return;

  const trs = document.querySelectorAll(`[data-id]`);
  
  let trSeleccionado;

  for (const tr of trs) {
    if(tr.getAttribute("data-id") == e.target.id){
      trSeleccionado = tr;
    }
  }

  const dialog = document.createElement("dialog");

  dialog.style.cssText = `
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    margin: 0;
    padding: 30px;
    background: white;
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    width: 500px;
    text-align: center;
  `;

  const backdropStyle = document.createElement('style');
  backdropStyle.textContent = `
    dialog::backdrop {
      background: rgba(0, 0, 0, 0.3);
      backdrop-filter: blur(5px);
      -webkit-backdrop-filter: blur(5px);
    }
  `;
  document.head.appendChild(backdropStyle);

  const h2 = document.createElement("h2");
  h2.textContent = `Editar alumno: ${trSeleccionado.getAttribute("data-id")}`;
  h2.style.marginBottom = "20px";
  h2.style.color = "black";

  const form = document.createElement("form");
  form.classList.add("text-black");
  form.action = `/forexfalcon/alumnosTabla`;
  form.method = "post";

  const divDNI = document.createElement("div");
  divDNI.classList.add("flex-column");
  divDNI.classList.add("gap-2");
  divDNI.classList.add("text-start");
  
  const labelDNI = document.createElement("label");
  const inputDNI = document.createElement("input");
  const inputDNIHidden = document.createElement("input");

  labelDNI.textContent = "DNI:";
  labelDNI.classList.add("form-label");

  inputDNI.value = trSeleccionado.getAttribute("data-id");
  inputDNI.classList.add("form-control");
  inputDNI.disabled = true;
  inputDNI.maxLength = 10;

  inputDNIHidden.type = "hidden";
  inputDNIHidden.name = "updateDNI";
  inputDNIHidden.value = trSeleccionado.getAttribute("data-id");

  divDNI.append(labelDNI,inputDNI,inputDNIHidden);
  
  const divUsuario = document.createElement("div");
  divUsuario.classList.add("flex-column");
  divUsuario.classList.add("gap-2");
  divUsuario.classList.add("text-start");

  const labelUsuario = document.createElement("label");
  const inputUsuario = document.createElement("input");
  
  labelUsuario.textContent = "Usuario:";
  labelUsuario.classList.add("form-label");

  inputUsuario.classList.add("form-control");
  inputUsuario.name = "updateUsuario";
  inputUsuario.value = trSeleccionado.getAttribute("data-usuario");

  divUsuario.append(labelUsuario,inputUsuario);
  
  const divCCC = document.createElement("div");
  divCCC.classList.add("flex-column");
  divCCC.classList.add("gap-2");
  divCCC.classList.add("text-start");

  const labelCCC = document.createElement("label");
  const inputCCC = document.createElement("input");
  
  labelCCC.textContent = "CCC:";
  labelCCC.classList.add("form-label");

  inputCCC.classList.add("form-control");
  inputCCC.name = "updateCCC";
  inputCCC.type = "text";
  inputCCC.value = trSeleccionado.getAttribute("data-ccc");

  divCCC.append(labelCCC,inputCCC);
  
  const divMatricula = document.createElement("div");
  divMatricula.classList.add("flex-column");
  divMatricula.classList.add("gap-2");
  divMatricula.classList.add("text-start");

  const labelMatricula = document.createElement("label");
  const selectMatricula = document.createElement("select");

  const optionSeleccionada = document.createElement("option");
  const optionVarianteUno = document.createElement("option");
  const optionVarianteDos = document.createElement("option");

  labelMatricula.textContent = "Matricula:";
  labelMatricula.classList.add("form-label");

  selectMatricula.classList.add("form-control");
  selectMatricula.name = "updateMatricula";

  optionSeleccionada.textContent = trSeleccionado.getAttribute("data-matricula");
  optionSeleccionada.value = trSeleccionado.getAttribute("data-matricula");

  if(optionSeleccionada.textContent == "Pendiente"){
    optionVarianteUno.textContent = "Promoción";
    optionVarianteUno.value = "PPromoción";
    optionVarianteDos.textContent = "Precio pagado";
    optionVarianteDos.value = "Precio pagado";
  } else if(optionSeleccionada.textContent == "Promoción"){
    optionVarianteUno.textContent = "Pendiente";
    optionVarianteUno.value = "Pendiente";
    optionVarianteDos.textContent = "Precio pagado";
    optionVarianteDos.value = "Precio pagado";
  } else if(optionSeleccionada.textContent == "Precio pagado"){
    optionVarianteUno.textContent = "Pendiente";
    optionVarianteUno.value = "Pendiente";
    optionVarianteDos.textContent = "Promoción";
    optionVarianteDos.value = "Promoción";
  }

  selectMatricula.append(optionSeleccionada, optionVarianteUno, optionVarianteDos);

  divMatricula.append(labelMatricula,selectMatricula);

  const divButtoms = document.createElement("div");
  divButtoms.classList.add("flex-column");
  divButtoms.classList.add("gap-2");
  divButtoms.classList.add("mt-3");

  const btnAceptar = document.createElement("button");
  const btnCerrar = document.createElement("button");

  btnAceptar.textContent = "Guardar";
  btnAceptar.type = "submit";
  btnCerrar.textContent = "Cancelar";

  [btnAceptar, btnCerrar].forEach(btn => {
    btn.style.cssText = `
      padding: 10px 20px;
      margin: 5px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    `;
  });

  btnAceptar.style.background = "#4CAF50";
  btnAceptar.style.color = "white";
  btnCerrar.style.background = "#f44336";
  btnCerrar.style.color = "white";
  
  divButtoms.append(btnAceptar,btnCerrar);

  form.append(divDNI,divUsuario,divCCC,divMatricula,divButtoms);
  
  btnAceptar.addEventListener("click", () => {
    form.submit();
  });

  btnCerrar.addEventListener("click", () => {
    dialog.close();
    dialog.remove();
    backdropStyle.remove();
  });

  dialog.append(h2, form);

  document.body.append(dialog);
  dialog.showModal();
}





// Funcion de delete para los Alumno
const deleteAlumno = (e) =>{

  if(e.target.tagName != "BUTTOM" && !e.target.classList.contains("btnDarAlta") && !e.target.classList.contains("bi-trash-fill")) return;

  const dialog = document.createElement("dialog");

  dialog.style.cssText = `
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    margin: 0;
    padding: 30px;
    background: white;
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    width: 400px;
    text-align: center;
  `;

  const backdropStyle = document.createElement('style');
  backdropStyle.textContent = `
    dialog::backdrop {
      background: rgba(0, 0, 0, 0.3);
      backdrop-filter: blur(5px);
      -webkit-backdrop-filter: blur(5px);
    }
  `;
  document.head.appendChild(backdropStyle);

  const h2 = document.createElement("h2");
  h2.textContent = "¿Quieres borrar el alumno?";
  h2.style.marginBottom = "20px";
  h2.style.color = "black";

  const form = document.createElement("form");
  form.action = `/forexfalcon/alumnosTabla`;
  form.method = "post";

  const inputDNI = document.createElement("input");
  inputDNI.type = "hidden";
  inputDNI.name = "deleteDNI";

  const btnAceptar = document.createElement("button");
  const btnCerrar = document.createElement("button");
  btnAceptar.textContent = "Borrar alumno";
  btnAceptar.type = "submit";
  btnCerrar.textContent = "Cancelar";

  [btnAceptar, btnCerrar].forEach(btn => {
    btn.style.cssText = `
      padding: 10px 20px;
      margin: 5px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    `;
  });

  btnAceptar.style.background = "#4CAF50";
  btnAceptar.style.color = "white";
  btnCerrar.style.background = "#f44336";
  btnCerrar.style.color = "white";

  form.append(inputDNI,btnAceptar, btnCerrar);
  dialog.append(h2, form);

  btnAceptar.addEventListener("click", () => {
    const trs = document.querySelectorAll(`[data-id]`);
  
    let trSeleccionado;

    for (const tr of trs) {
      if(tr.getAttribute("data-id") == e.target.id){
        trSeleccionado = tr;
      }
    }

    inputDNI.value = trSeleccionado.getAttribute("data-id");
    form.submit();
  });

  btnCerrar.addEventListener("click", () => {
    dialog.close();
    dialog.remove();
    backdropStyle.remove();
  });

  document.body.append(dialog);
  dialog.showModal();
}


///////////////////
// Main
///////////////////

const tablaAlumnos = document.querySelector("#tablaAlumnos");

tablaAlumnos.addEventListener("click", editarAlumno);
tablaAlumnos.addEventListener("click", deleteAlumno);

// Jquery - DataTable
$(document).ready(function() {
  var tablaAlumnos;
  if ($('#tablaAlumnos').length > 0) {
    tablaAlumnos = $('#tablaAlumnos').DataTable({
      responsive: true,
      "pageLength": 6,
      "order": [],
      "language": {
        "sProcessing": "Procesando...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningún dato disponible en esta tabla",
        "sInfo": "",
        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered": "(filtrado de un total de MAX registros)",
        "sSearch": "", 
        "sSearchPlaceholder": "Buscar curso...",
        "sLoadingRecords": "Cargando...",
      },
      "layout": { 
        top: null, 
        topStart: "pageLength", 
        topEnd: 'search',
        bottom: 'paging', 
        bottomStart: null,
        bottomEnd: null 
      }
    });
  }
});