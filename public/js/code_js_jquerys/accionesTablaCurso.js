"user strict";

///////////////////
// Functions
///////////////////

// Funcion de crear para los cursos
const crearCurso = () =>{
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
  h2.textContent = "Crear curso";
  h2.style.marginBottom = "20px";
  h2.style.color = "black";

  const form = document.createElement("form");
  form.classList.add("text-black");
  form.action = `/forexfalcon/cursosTabla`;
  form.method = "post";

  const divClave = document.createElement("div");
  divClave.classList.add("flex-column");
  divClave.classList.add("gap-2");
  divClave.classList.add("text-start");
  
  const labelClave = document.createElement("label");
  const inputClave = document.createElement("input");

  labelClave.textContent = "Clave:";
  labelClave.classList.add("form-label");

  inputClave.classList.add("form-control");
  inputClave.name = "clave";
  inputClave.placeholder = "Ej: ADR";
  inputClave.maxLength = 10;

  divClave.append(labelClave,inputClave);
  
  const divDescrip = document.createElement("div");
  divDescrip.classList.add("flex-column");
  divDescrip.classList.add("gap-2");
  divDescrip.classList.add("text-start");

  const labelDescrip = document.createElement("label");
  const inputDescrip = document.createElement("input");
  
  labelDescrip.textContent = "Descripción:";
  labelDescrip.classList.add("form-label");

  inputDescrip.classList.add("form-control");
  inputDescrip.name = "descripcion";
  inputDescrip.placeholder = "Ej: Mercancías";

  divDescrip.append(labelDescrip,inputDescrip);
  
  const divPrecioMes = document.createElement("div");
  divPrecioMes.classList.add("flex-column");
  divPrecioMes.classList.add("gap-2");
  divPrecioMes.classList.add("text-start");

  const labelPrecioMes = document.createElement("label");
  const inputPrecioMes = document.createElement("input");
  
  labelPrecioMes.textContent = "Precio del mes:";
  labelPrecioMes.classList.add("form-label");

  inputPrecioMes.classList.add("form-control");
  inputPrecioMes.name = "precioMes";
  inputPrecioMes.type = "number";
  inputPrecioMes.placeholder = "Ej: 100";
  inputPrecioMes.step = "0.01";
  inputPrecioMes.min = "0";

  divPrecioMes.append(labelPrecioMes,inputPrecioMes);
  
  const divMatricula = document.createElement("div");
  divMatricula.classList.add("flex-column");
  divMatricula.classList.add("gap-2");
  divMatricula.classList.add("text-start");

  const labelMatricula = document.createElement("label");
  const inputMatricula = document.createElement("input");
  
  labelMatricula.textContent = "Matricula";
  labelMatricula.classList.add("form-label");

  inputMatricula.classList.add("form-control");
  inputMatricula.name = "matricula";
  inputMatricula.type = "number";
  inputMatricula.placeholder = "Ej: 100";
  inputMatricula.step = "0.01";
  inputMatricula.min = "0";

  divMatricula.append(labelMatricula,inputMatricula);
  
  const divBaja = document.createElement("div");
  divBaja.classList.add("flex-column");
  divBaja.classList.add("gap-2");
  divBaja.classList.add("text-start");

  const labelBaja = document.createElement("label");
  const selectBaja = document.createElement("select");
  const si = document.createElement("option");
  const no = document.createElement("option");
  
  labelBaja.textContent = "Baja:";
  labelBaja.classList.add("form-label");

  selectBaja.classList.add("form-control");
  selectBaja.name = "baja";

  si.textContent = "Si";
  si.value = "si";
  no.textContent = "No";
  no.value = "no";

  selectBaja.append(si,no);
  divBaja.append(labelBaja,selectBaja);
  
  const divNotas = document.createElement("div");
  divNotas.classList.add("flex-column");
  divNotas.classList.add("gap-2");
  divNotas.classList.add("text-start");

  const labelNotas = document.createElement("label");
  const inputNotas = document.createElement("textarea");
  
  labelNotas.textContent = "Notas:";
  labelNotas.classList.add("form-label");

  inputNotas.classList.add("form-control");
  inputNotas.name = "notas";
  inputNotas.placeholder = "Ej: Curso básico de 20 horas";
  inputNotas.rows = 3;

  divNotas.append(labelNotas,inputNotas);

  const divButtoms = document.createElement("div");
  divButtoms.classList.add("flex-column");
  divButtoms.classList.add("gap-2");
  divButtoms.classList.add("mt-3");

  const btnAceptar = document.createElement("button");
  const btnCerrar = document.createElement("button");

  btnAceptar.textContent = "Crear";
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

  form.append(divClave,divDescrip,divPrecioMes,divMatricula,divBaja,divNotas,divButtoms);
  
  btnCerrar.addEventListener("click", () => {
    dialog.close();
    dialog.remove();
    backdropStyle.remove();
  });

  dialog.append(h2, form);

  document.body.append(dialog);
  dialog.showModal();
}





// Funcion de editar para los cursos
const editarCurso = (e) =>{

  if(e.target.tagName != "BUTTOM" && !e.target.classList.contains("btnEditarCurso") && !e.target.classList.contains("bi-pen-fill")) return;
  
  const trs = document.querySelectorAll("tr");
  
  let accionesClave;

  for (const tr of trs) {
    if(tr.id == e.target.id){
      accionesClave = e.target.id;
    }
  }

  const trSeleccionado = document.querySelector(`#${accionesClave}`).children;

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
  h2.textContent = `Editar curso: ${trSeleccionado[0].textContent}`;
  h2.style.marginBottom = "20px";
  h2.style.color = "black";

  const form = document.createElement("form");
  form.classList.add("text-black");
  form.action = `/forexfalcon/cursosTabla`;
  form.method = "post";

  const divClave = document.createElement("div");
  divClave.classList.add("flex-column");
  divClave.classList.add("gap-2");
  divClave.classList.add("text-start");
  
  const labelClave = document.createElement("label");
  const inputClave = document.createElement("input");
  const inputClaveHidden = document.createElement("input");

  labelClave.textContent = "Clave:";
  labelClave.classList.add("form-label");

  inputClave.value = trSeleccionado[0].textContent;
  inputClave.classList.add("form-control");
  inputClave.disabled = true;
  inputClave.maxLength = 10;

  inputClaveHidden.type = "hidden";
  inputClaveHidden.name = "updateClave";
  inputClaveHidden.value = trSeleccionado[0].textContent;

  divClave.append(labelClave,inputClave,inputClaveHidden);
  
  const divDescrip = document.createElement("div");
  divDescrip.classList.add("flex-column");
  divDescrip.classList.add("gap-2");
  divDescrip.classList.add("text-start");

  const labelDescrip = document.createElement("label");
  const inputDescrip = document.createElement("input");
  
  labelDescrip.textContent = "Descripción:";
  labelDescrip.classList.add("form-label");

  inputDescrip.classList.add("form-control");
  inputDescrip.name = "updateDescripcion";
  inputDescrip.value = trSeleccionado[1].textContent;

  divDescrip.append(labelDescrip,inputDescrip);
  
  const divPrecioMes = document.createElement("div");
  divPrecioMes.classList.add("flex-column");
  divPrecioMes.classList.add("gap-2");
  divPrecioMes.classList.add("text-start");

  const labelPrecioMes = document.createElement("label");
  const inputPrecioMes = document.createElement("input");
  
  labelPrecioMes.textContent = "Precio del mes:";
  labelPrecioMes.classList.add("form-label");

  inputPrecioMes.classList.add("form-control");
  inputPrecioMes.name = "updatePrecioMes";
  inputPrecioMes.type = "number";
  inputPrecioMes.value = trSeleccionado[2].textContent;

  divPrecioMes.append(labelPrecioMes,inputPrecioMes);
  
  const divMatricula = document.createElement("div");
  divMatricula.classList.add("flex-column");
  divMatricula.classList.add("gap-2");
  divMatricula.classList.add("text-start");

  const labelMatricula = document.createElement("label");
  const inputMatricula = document.createElement("input");
  
  labelMatricula.textContent = "Matricula";
  labelMatricula.classList.add("form-label");

  inputMatricula.classList.add("form-control");
  inputMatricula.name = "updateMatricula";
  inputMatricula.type = "number";
  inputMatricula.value = trSeleccionado[3].textContent;

  divMatricula.append(labelMatricula,inputMatricula);
  
  const divBaja = document.createElement("div");
  divBaja.classList.add("flex-column");
  divBaja.classList.add("gap-2");
  divBaja.classList.add("text-start");

  const labelBaja = document.createElement("label");
  const selectBaja = document.createElement("select");
  const si = document.createElement("option");
  const no = document.createElement("option");
  
  labelBaja.textContent = "Baja:";
  labelBaja.classList.add("form-label");

  selectBaja.classList.add("form-control");
  selectBaja.name = "updateBaja";

  si.textContent = trSeleccionado[4].textContent;
  si.value = trSeleccionado[4].textContent;
  
  if(si.value == "No" || si.value == "no"){
    no.textContent = "Si";
    no.value = "Si"
  } else {
    no.textContent = "No";
    no.value = "No"
  }

  selectBaja.append(si,no);
  divBaja.append(labelBaja,selectBaja);
  
  const divNotas = document.createElement("div");
  divNotas.classList.add("flex-column");
  divNotas.classList.add("gap-2");
  divNotas.classList.add("text-start");

  const labelNotas = document.createElement("label");
  const inputNotas = document.createElement("textarea");
  
  labelNotas.textContent = "Notas:";
  labelNotas.classList.add("form-label");

  inputNotas.classList.add("form-control");
  inputNotas.value = trSeleccionado[5].textContent;
  inputNotas.name = "updateNotas";
  inputNotas.rows = 3;

  divNotas.append(labelNotas,inputNotas);

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

  form.append(divClave,divDescrip,divPrecioMes,divMatricula,divBaja,divNotas,divButtoms);
  
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





const darBajaCurso = (e) =>{

  if(e.target.tagName != "BUTTOM" && !e.target.classList.contains("btnDarBaja") && !e.target.classList.contains("bi-dash-circle-fill")) return;

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
  h2.textContent = "¿Quieres dar de baja al curso?";
  h2.style.marginBottom = "20px";
  h2.style.color = "black";

  const p = document.createElement("p");
  p.classList.add("text-black");
  p.textContent = "NOTA: si das de baja a este curso todos los alumnos unidos a el no tendran permisos para acceder a los recursos"

  const form = document.createElement("form");
  form.action = `/forexfalcon/cursosTabla`;
  form.method = "post";

  const inputClave = document.createElement("input");
  inputClave.type = "hidden";
  inputClave.name = "accionesClave";
  const inputBaja = document.createElement("input");
  inputBaja.type = "hidden";
  inputBaja.name = "accionesBaja";
  inputBaja.value = "Si";

  const btnAceptar = document.createElement("button");
  const btnCerrar = document.createElement("button");
  btnAceptar.textContent = "Dar de baja";
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

  form.append(inputClave,inputBaja,btnAceptar, btnCerrar);
  dialog.append(h2, p, form);

  btnAceptar.addEventListener("click", () => {
    const trs = document.querySelectorAll("tr");
    let accionesClave = "";
    for (const tr of trs) {
      if(tr.id == e.target.id){
        accionesClave = e.target.id;
      }
    }
    inputClave.value = accionesClave;
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





const darAltaCurso = (e) =>{

  if(e.target.tagName != "BUTTOM" && !e.target.classList.contains("btnDarAlta") && !e.target.classList.contains("bi-plus-circle-fill")) return;

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
  h2.textContent = "¿Quieres dar de alta al curso?";
  h2.style.marginBottom = "20px";
  h2.style.color = "black";
  
  const p = document.createElement("p");
  p.classList.add("text-black");
  p.textContent = "NOTA: si das de alta a este curso todos los alumnos unidos a el tendran permisos para acceder a los recursos";


  const form = document.createElement("form");
  form.action = `/forexfalcon/cursosTabla`;
  form.method = "post";

  const inputClave = document.createElement("input");
  inputClave.type = "hidden";
  inputClave.name = "accionesClave";
  const inputBaja = document.createElement("input");
  inputBaja.type = "hidden";
  inputBaja.name = "accionesBaja";
  inputBaja.value = "No";

  const btnAceptar = document.createElement("button");
  const btnCerrar = document.createElement("button");
  btnAceptar.textContent = "Dar de alta";
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

  form.append(inputClave,inputBaja,btnAceptar, btnCerrar);
  dialog.append(h2, p, form);

  btnAceptar.addEventListener("click", () => {
    const trs = document.querySelectorAll("tr");
    let accionesClave = "";
    for (const tr of trs) {
      if(tr.id == e.target.id){
        accionesClave = e.target.id;
      }
    }
    inputClave.value = accionesClave;
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

const tablaCursos = document.querySelector("#tablaCursos");

document.querySelector("#btnCrearCurso").addEventListener("click", crearCurso);
tablaCursos.addEventListener("click", editarCurso);
tablaCursos.addEventListener("click", darBajaCurso);
tablaCursos.addEventListener("click", darAltaCurso);

// Jquery - DataTable
$(document).ready(function() {
  var tablaCursos;
  if ($('#tablaCursos').length > 0) {
    tablaCursos = $('#tablaCursos').DataTable({
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