"user strict";

///////////////////
// Functions
///////////////////

// Tratar JSONs
const tratarJsoncaracteristicasAlumnos = async () =>{
  try {

    const response = await fetch("./public/json/caracteristicasAlumnos.json");

    if(!response.ok)
      throw new Error("No se ha encontrado el archivo .json");

    const json = await response.json();

    return json;

  } catch (error) {
    console.error(error);
  }
}

const tratarJsonClaveCursos = async () =>{
  try {

    const response = await fetch("./public/json/claveCursos.json");

    if(!response.ok)
      throw new Error("No se ha encontrado el archivo .json");

    const json = await response.json();

    return json;

  } catch (error) {
    console.error(error);
  }
}





// Funcion de crear para los matriculados
const crearMatriculado = async () =>{

  const caracteristicasAlumnos = await tratarJsoncaracteristicasAlumnos();
  const claveCursos = await tratarJsonClaveCursos();

  let arrayClaveCurso = [];

  for (const clave of claveCursos) {
    if(!arrayClaveCurso.some(e => e === clave["claveCurso"])){
      arrayClaveCurso.push(clave["claveCurso"]);
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
  h2.textContent = "Crear matriculado";
  h2.style.marginBottom = "20px";
  h2.style.color = "black";

  const form = document.createElement("form");
  form.classList.add("text-black");
  form.action = `/matriculadosTabla`;
  form.method = "post";

  const divDNI = document.createElement("div");
  divDNI.classList.add("flex-column");
  divDNI.classList.add("gap-2");
  divDNI.classList.add("text-start");
  
  const labelDNI = document.createElement("label");
  const selectDNI = document.createElement("select");

  labelDNI.textContent = "DNI:";
  labelDNI.classList.add("form-label");

  selectDNI.classList.add("form-control");
  selectDNI.name = "DNI";

  for (const caracteristica of caracteristicasAlumnos) {
    const option = document.createElement("option");

    option.textContent = `${caracteristica["DNI"]} - ${caracteristica["nombre"]} ${caracteristica["apellido"]}`;
    option.value = caracteristica["DNI"];

    selectDNI.append(option);
  }

  divDNI.append(labelDNI,selectDNI);
  
  const divClaveCurso = document.createElement("div");
  divClaveCurso.classList.add("flex-column");
  divClaveCurso.classList.add("gap-2");
  divClaveCurso.classList.add("text-start");

  const labelClaveCurso = document.createElement("label");
  const selectClaveCurso = document.createElement("select");

  labelClaveCurso.textContent = "Clave del curso:";
  labelClaveCurso.classList.add("form-label");

  selectClaveCurso.classList.add("form-control");
  selectClaveCurso.name = "claveCurso";
  
  for (const clave of arrayClaveCurso) {
    const optionClaveCurso = document.createElement("option");

    optionClaveCurso.textContent = clave;
    optionClaveCurso.value = clave;

    selectClaveCurso.append(optionClaveCurso);
  }
  
  divClaveCurso.append(labelClaveCurso,selectClaveCurso);
  
  const divCertificado = document.createElement("div");
  divCertificado.classList.add("flex-column");
  divCertificado.classList.add("gap-2");
  divCertificado.classList.add("text-start");

  const labelCertificado = document.createElement("label");
  const selectCertificado = document.createElement("select");
  const siCertificado = document.createElement("option");
  const noCertificado = document.createElement("option");
  
  labelCertificado.textContent = "Certificado:";
  labelCertificado.classList.add("form-label");

  selectCertificado.classList.add("form-control");
  selectCertificado.name = "certificado";
  
  siCertificado.textContent = "Si";
  siCertificado.value = "Si";
  noCertificado.textContent = "No";
  noCertificado.value = "No";

  selectCertificado.append(noCertificado,siCertificado);
  divCertificado.append(labelCertificado,selectCertificado);
  
  const divAlta = document.createElement("div");
  divAlta.classList.add("flex-column");
  divAlta.classList.add("gap-2");
  divAlta.classList.add("text-start");

  const labelAlta = document.createElement("label");
  const selectAlta = document.createElement("select");
  const siAlta = document.createElement("option");
  const noAlta = document.createElement("option");

  labelAlta.textContent = "Alta:";
  labelAlta.classList.add("form-label");

  selectAlta.classList.add("form-control");
  selectAlta.name = "alta";

  siAlta.textContent = "Si";
  siAlta.value = "Si";
  noAlta.textContent = "No";
  noAlta.value = "No";
  
  selectAlta.append(siAlta,noAlta);
  divAlta.append(labelAlta,selectAlta);

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

  form.append(divDNI,divClaveCurso,divCertificado,divAlta,divNotas,divButtoms);
  
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






// Funcion de editar para los matriculados
const editarMatriculado = (e) =>{

  if(e.target.tagName != "BUTTOM" && !e.target.classList.contains("btnEditarCurso") && !e.target.classList.contains("bi-pen-fill")) return;

  const trs = document.querySelectorAll(`[data-id-tr]`);
  
  let trSeleccionado;

  for (const tr of trs) {
    if(tr.getAttribute("data-id-tr") == e.target.getAttribute("data-id") && tr.getAttribute("data-claveCurso-tr") == e.target.getAttribute("data-claveCurso")){
      trSeleccionado = tr.children;
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
  h2.textContent = `Editar matriculado: ${trSeleccionado[2].textContent} ${trSeleccionado[3].textContent}`;
  h2.style.marginBottom = "20px";
  h2.style.color = "black";

  const form = document.createElement("form");
  form.classList.add("text-black");
  form.action = `/matriculadosTabla`;
  form.method = "post";

  const divDNI = document.createElement("div");
  divDNI.classList.add("flex-column");
  divDNI.classList.add("gap-2");
  divDNI.classList.add("text-start");
  
  const labelDNI = document.createElement("label");
  const inputDNI = document.createElement("input");
  const inputDNIHidden = document.createElement("input");

  labelDNI.textContent = "ID:";
  labelDNI.classList.add("form-label");

  inputDNI.value = trSeleccionado[0].textContent;
  inputDNI.classList.add("form-control");
  inputDNI.disabled = true;
  inputDNI.maxLength = 10;

  inputDNIHidden.type = "hidden";
  inputDNIHidden.name = "updateDNI";
  inputDNIHidden.value = trSeleccionado[0].textContent;

  divDNI.append(labelDNI,inputDNI,inputDNIHidden);
  
  const divClaveCurso = document.createElement("div");
  divClaveCurso.classList.add("flex-column");
  divClaveCurso.classList.add("gap-2");
  divClaveCurso.classList.add("text-start");

  const labelClaveCurso = document.createElement("label");
  const inputClaveCurso = document.createElement("input");
  const inputClaveCursoHidden = document.createElement("input");
  
  labelClaveCurso.textContent = "Clave del curso:";
  labelClaveCurso.classList.add("form-label");

  inputClaveCurso.disabled = true;
  inputClaveCurso.classList.add("form-control");
  inputClaveCurso.type = "text";
  inputClaveCurso.value = trSeleccionado[1].textContent;
  
  inputClaveCursoHidden.classList.add("form-control");
  inputClaveCursoHidden.name = "updateClaveCurso";
  inputClaveCursoHidden.type = "hidden";
  inputClaveCursoHidden.value = trSeleccionado[1].textContent;

  divClaveCurso.append(labelClaveCurso,inputClaveCurso,inputClaveCursoHidden);

  const divCertificado = document.createElement("div");
  divCertificado.classList.add("flex-column");
  divCertificado.classList.add("gap-2");
  divCertificado.classList.add("text-start");

  const labelCertificado = document.createElement("label");
  const selectCertificado = document.createElement("select");
  const si = document.createElement("option");
  const no = document.createElement("option");
  
  labelCertificado.textContent = "Certificado:";
  labelCertificado.classList.add("form-label");

  selectCertificado.classList.add("form-control");
  selectCertificado.name = "updateCertificado";

  si.textContent = trSeleccionado[6].textContent;
  si.value = trSeleccionado[6].textContent;
  
  if(si.value == "No"){
    no.textContent = "Si";
    no.value = "Si"
  } else {
    no.textContent = "No";
    no.value = "No"
  }

  selectCertificado.append(si,no);
  divCertificado.append(labelCertificado,selectCertificado);

  const divAlta = document.createElement("div");
  divAlta.classList.add("flex-column");
  divAlta.classList.add("gap-2");
  divAlta.classList.add("text-start");

  const labelAlta = document.createElement("label");
  const selectAlta = document.createElement("select");
  const siAlta = document.createElement("option");
  const noAlta = document.createElement("option");

  labelAlta.textContent = "Alta:";
  labelAlta.classList.add("form-label");

  selectAlta.classList.add("form-control");
  selectAlta.name = "updateAlta";

  siAlta.textContent = trSeleccionado[7].textContent;
  siAlta.value = trSeleccionado[7].textContent;

  if(siAlta.value == "No"){
    noAlta.textContent = "Si";
    noAlta.value = "Si"
  } else {
    noAlta.textContent = "No";
    noAlta.value = "No"
  }
  selectAlta.append(siAlta,noAlta);
  divAlta.append(labelAlta,selectAlta);

  const divNotas = document.createElement("div");
  divNotas.classList.add("flex-column");
  divNotas.classList.add("gap-2");
  divNotas.classList.add("text-start");

  const labelNotas = document.createElement("label");
  const inputNotas = document.createElement("textarea");
  
  labelNotas.textContent = "Notas:";
  labelNotas.classList.add("form-label");

  inputNotas.classList.add("form-control");
  inputNotas.name = "updateNotas";
  inputNotas.value = trSeleccionado[8].textContent;

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

  form.append(divDNI,divDNI,divClaveCurso,divCertificado,divAlta,divNotas,divButtoms);
  
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








// Funcion de delete/alta para los matriculados
const altaMatriculado = (e) =>{

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
  h2.textContent = "¿Quieres dar de alta?";
  h2.style.marginBottom = "20px";
  h2.style.color = "black";

  const form = document.createElement("form");
  form.action = `/matriculadosTabla`;
  form.method = "post";

  const inputDNI = document.createElement("input");
  inputDNI.type = "hidden";
  inputDNI.name = "altaDNI";

  const inputClaveCurso = document.createElement("input");
  inputClaveCurso.type = "hidden";
  inputClaveCurso.name = "altaClaveCurso";

  const inputAlta = document.createElement("input");
  inputAlta.type = "hidden";
  inputAlta.name = "altaAlta";
  inputAlta.value = "Si";

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

  form.append(inputDNI, inputClaveCurso, inputAlta, btnAceptar, btnCerrar);
  dialog.append(h2, form);

  btnAceptar.addEventListener("click", () => {
    const trs = document.querySelectorAll(`[data-id-tr]`);
  
    let trSeleccionado;

    for (const tr of trs) {
      if(tr.getAttribute("data-id-tr") == e.target.getAttribute("data-id") && tr.getAttribute("data-claveCurso-tr") == e.target.getAttribute("data-claveCurso")){
        trSeleccionado = tr;
        console.log(trSeleccionado);
      }
    }

    inputDNI.value = trSeleccionado.getAttribute("data-id-tr");
    inputClaveCurso.value = trSeleccionado.getAttribute("data-claveCurso-tr");
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





const bajaMatriculado = (e) =>{

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
  h2.textContent = "¿Quieres dar de baja?";
  h2.style.marginBottom = "20px";
  h2.style.color = "black";

  const form = document.createElement("form");
  form.action = `/matriculadosTabla`;
  form.method = "post";

  const inputDNI = document.createElement("input");
  inputDNI.type = "hidden";
  inputDNI.name = "altaDNI";

  const inputClaveCurso = document.createElement("input");
  inputClaveCurso.type = "hidden";
  inputClaveCurso.name = "altaClaveCurso";

  const inputAlta = document.createElement("input");
  inputAlta.type = "hidden";
  inputAlta.name = "altaAlta";
  inputAlta.value = "No";

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

  form.append(inputDNI, inputClaveCurso, inputAlta, btnAceptar, btnCerrar);
  dialog.append(h2, form);

  btnAceptar.addEventListener("click", () => {
    const trs = document.querySelectorAll(`[data-id-tr]`);
  
    let trSeleccionado;

    for (const tr of trs) {
      if(tr.getAttribute("data-id-tr") == e.target.getAttribute("data-id") && tr.getAttribute("data-claveCurso-tr") == e.target.getAttribute("data-claveCurso")){
        trSeleccionado = tr;
        console.log(trSeleccionado);
      }
    }

    inputDNI.value = trSeleccionado.getAttribute("data-id-tr");
    inputClaveCurso.value = trSeleccionado.getAttribute("data-claveCurso-tr");
    
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

// Llamadas a funciones
const tablaMatriculados = document.querySelector("#tablaMatriculados");

document.querySelector("#btnCrearMatriculado").addEventListener("click", crearMatriculado);
tablaMatriculados.addEventListener("click", editarMatriculado);
tablaMatriculados.addEventListener("click", altaMatriculado);
tablaMatriculados.addEventListener("click", bajaMatriculado);

// Jquery - DataTable
$(document).ready(function() {
  var tablaMatriculados;
  if ($('#tablaMatriculados').length > 0) {
    tablaMatriculados = $('#tablaMatriculados').DataTable({
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
