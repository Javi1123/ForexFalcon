"user strict";

///////////////////
// Functions
///////////////////

// Tratar los JSON
const tratarJsonDnisMatriculados = async () =>{
  try {

    const response = await fetch("./public/json/dnisMatriculados.json");

    if(!response.ok)
      throw new Error("No se ha encontrado el archivo .json");

    const json = await response.json();

    return json;

  } catch (error) {
    console.error(error);
  }
}

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





// Funcion de crear para los recibos
const crearRecibo = async () => {
  const dnisMatriculados = await tratarJsonDnisMatriculados();
  const caracteristicasAlumnos = await tratarJsoncaracteristicasAlumnos();

  let arrayMatriculados = [];

  for (const dni of dnisMatriculados) {
    if (!arrayMatriculados.some(e => e["DNI"] === dni["DNI"])) {
      const matriculado = caracteristicasAlumnos.find(e => e["DNI"] === dni["DNI"]);
      if (matriculado) {
        dni.nombre = matriculado["nombre"];
        dni.apellido = matriculado["apellido"];
      }
      arrayMatriculados.push(dni);
    }
  }

  // --- Diálogo y estilos ---
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

  // --- Cabecera con pestañas ---
  const divFlex = document.createElement("div");
  const divOpciones = document.createElement("div");
  divFlex.classList.add("flex-row");
  divOpciones.classList.add("row");

  const unico = document.createElement("span");
  unico.textContent = "Crear recibo únicos";
  unico.style.marginBottom = "20px";
  unico.style.cursor = "pointer";
  unico.classList.add("h4", "py-2", "col-6", "reciboCrearSeleccionado");

  const varios = document.createElement("span");
  varios.textContent = "Generar varios recibos";
  varios.style.marginBottom = "20px";
  varios.style.cursor = "pointer";
  varios.classList.add("h4", "py-2", "col-6", "reciboCrearNoSeleccionado");

  divOpciones.append(varios, unico);
  divFlex.append(divOpciones);

  const formContainer = document.createElement("div");
  divFlex.appendChild(formContainer);

  // Función común para cerrar el diálogo y limpiar estilos
  const cerrarDialogo = () => {
    dialog.close();
    dialog.remove();
    backdropStyle.remove();
    tabStyle.remove();
  };

  // --- Formulario para recibo único ---
  const buildUnicoForm = () => {
    const form = document.createElement("form");
    form.classList.add("text-black");
    form.action = "/forexfalcon/recibosTabla";
    form.method = "post";

    // DNI
    const divDNI = document.createElement("div");
    divDNI.classList.add("flex-column", "gap-2", "text-start");
    const labelDNI = document.createElement("label");
    const selectDNI = document.createElement("select");
    labelDNI.textContent = "DNI:";
    labelDNI.classList.add("form-label");
    selectDNI.classList.add("form-control");
    selectDNI.name = "DNI";

    for (const dni of arrayMatriculados) {
      const option = document.createElement("option");
      option.textContent = `${dni["DNI"]} - ${dni["nombre"]} ${dni["apellido"]}`;
      option.value = dni["DNI"];
      selectDNI.append(option);
    }
    
    divDNI.append(labelDNI, selectDNI);

    // Clave curso
    const divClaveCurso = document.createElement("div");
    divClaveCurso.classList.add("flex-column", "gap-2", "text-start");
    const labelClaveCurso = document.createElement("label");
    const selectClaveCurso = document.createElement("select");
    labelClaveCurso.textContent = "Clave del curso:";
    labelClaveCurso.classList.add("form-label");
    selectClaveCurso.classList.add("form-control");
    selectClaveCurso.name = "claveCurso";
    divClaveCurso.append(labelClaveCurso, selectClaveCurso);

    const populateCursos = () => {
      selectClaveCurso.innerHTML = "";
      const dniSeleccionado = selectDNI.value;
      for (const clave of dnisMatriculados) {
        if (clave["DNI"] === dniSeleccionado) {
          const option = document.createElement("option");
          option.textContent = clave["claveCurso"];
          option.value = clave["claveCurso"];
          selectClaveCurso.append(option);
        }
      }
    };
    selectDNI.addEventListener("change", populateCursos);
    populateCursos();

    // Estado
    const divEstado = document.createElement("div");
    divEstado.classList.add("flex-column", "gap-2", "text-start");
    const labelEstado = document.createElement("label");
    const selectEstado = document.createElement("select");
    const si = document.createElement("option");
    const no = document.createElement("option");
    labelEstado.textContent = "Estado:";
    labelEstado.classList.add("form-label");
    selectEstado.classList.add("form-control");
    selectEstado.name = "estado";
    si.textContent = "Pagado";
    si.value = "Pagado";
    no.textContent = "No pagado";
    no.value = "No pagado";
    selectEstado.append(si, no);
    divEstado.append(labelEstado, selectEstado);

    // Cantidad
    const divCantidad = document.createElement("div");
    divCantidad.classList.add("flex-column", "gap-2", "text-start");
    const labelCantidad = document.createElement("label");
    const inputCantidad = document.createElement("input");
    labelCantidad.textContent = "Cantidad:";
    labelCantidad.classList.add("form-label");
    inputCantidad.classList.add("form-control");
    inputCantidad.name = "cantidad";
    inputCantidad.type = "number";
    inputCantidad.placeholder = "Ej: 100";
    inputCantidad.step = "0.01";
    inputCantidad.min = "0";
    divCantidad.append(labelCantidad, inputCantidad);

    // Botones
    const divButtoms = document.createElement("div");
    divButtoms.classList.add("flex-column", "gap-2", "mt-3");
    const btnAceptar = document.createElement("button");
    const btnCerrar = document.createElement("button");
    btnAceptar.textContent = "Crear";
    btnAceptar.type = "submit";
    btnCerrar.textContent = "Cancelar";
    [btnAceptar, btnCerrar].forEach(btn => {
      btn.style.cssText = `padding: 10px 20px; margin: 5px; border: none; border-radius: 5px; cursor: pointer;`;
    });
    btnAceptar.style.background = "#4CAF50";
    btnAceptar.style.color = "white";
    btnCerrar.style.background = "#f44336";
    btnCerrar.style.color = "white";
    divButtoms.append(btnAceptar, btnCerrar);

    form.append(divDNI, divClaveCurso, divEstado, divCantidad, divButtoms);

    btnAceptar.addEventListener("click", (e) => {
      e.preventDefault();
      form.submit();
    });
    btnCerrar.addEventListener("click", cerrarDialogo);
    return form;
  };

  // --- Formulario para varios recibos con CHECKBOXES ---
  const buildVariosForm = () => {
    const form = document.createElement("form");
    form.classList.add("text-black");
    form.action = "/forexfalcon/recibosTabla";
    form.method = "post";

    // Selector de curso
    const divCurso = document.createElement("div");
    divCurso.classList.add("flex-column", "gap-2", "text-start", "mb-3");
    const labelCurso = document.createElement("label");
    const selectCurso = document.createElement("select");
    labelCurso.textContent = "Curso:";
    labelCurso.classList.add("form-label");
    selectCurso.classList.add("form-control");
    selectCurso.name = "claveCurso";

    const cursosUnicos = [...new Set(dnisMatriculados.map(item => item["claveCurso"]))];
    for (const curso of cursosUnicos) {
      const option = document.createElement("option");
      option.value = curso;
      option.textContent = curso;
      selectCurso.appendChild(option);
    }
    divCurso.append(labelCurso, selectCurso);

    // Contenedor para los checkboxes de alumnos
    const divCheckboxes = document.createElement("div");
    divCheckboxes.classList.add("flex-column", "gap-2", "text-start", "mb-3");
    divCheckboxes.style.maxHeight = "200px";
    divCheckboxes.style.overflowY = "auto";
    divCheckboxes.style.border = "1px solid #ccc";
    divCheckboxes.style.padding = "10px";
    divCheckboxes.style.borderRadius = "5px";

    const labelCheckboxGroup = document.createElement("label");
    labelCheckboxGroup.textContent = "Seleccione los alumnos:";
    labelCheckboxGroup.classList.add("form-label");
    divCheckboxes.appendChild(labelCheckboxGroup);

    // Botón "Seleccionar todos"
    const btnSelectAll = document.createElement("button");
    btnSelectAll.textContent = "Seleccionar todos";
    btnSelectAll.type = "button";
    btnSelectAll.classList.add("btn", "btn-secondary", "btn-sm");
    btnSelectAll.style.marginBottom = "10px";
    btnSelectAll.style.fontSize = "0.8rem";
    divCheckboxes.appendChild(btnSelectAll);

    const checkboxesContainer = document.createElement("div");
    checkboxesContainer.classList.add("checkboxes-container");
    divCheckboxes.appendChild(checkboxesContainer);

    // Función para cargar checkboxes según el curso seleccionado
    const cargarCheckboxes = () => {
      const cursoSeleccionado = selectCurso.value;
      checkboxesContainer.innerHTML = "";

      const dnisDelCurso = dnisMatriculados
        .filter(item => item["claveCurso"] === cursoSeleccionado)
        .map(item => item["DNI"]);
      const dnisUnicos = [...new Set(dnisDelCurso)];

      for (const dni of dnisUnicos) {
        const alumno = arrayMatriculados.find(a => a["DNI"] === dni);
        const nombreMostrar = alumno ? `${dni} - ${alumno.nombre} ${alumno.apellido}` : dni;

        const divCheck = document.createElement("div");
        divCheck.classList.add("form-check");

        const checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.classList.add("form-check-input");
        checkbox.value = dni;
        checkbox.name = "DNIs[]";  // Envía un array al backend
        checkbox.id = `check_${dni}`;

        const label = document.createElement("label");
        label.classList.add("form-check-label");
        label.htmlFor = `check_${dni}`;
        label.textContent = nombreMostrar;

        divCheck.appendChild(checkbox);
        divCheck.appendChild(label);
        checkboxesContainer.appendChild(divCheck);
      }
    };

    // Seleccionar todos / ninguno
    btnSelectAll.addEventListener("click", () => {
      const allCheckboxes = checkboxesContainer.querySelectorAll('input[type="checkbox"]');
      const algunaSeleccionada = Array.from(allCheckboxes).some(cb => cb.checked);
      for (const cb of allCheckboxes) {
        cb.checked = !algunaSeleccionada;
      }
    });

    selectCurso.addEventListener("change", cargarCheckboxes);
    cargarCheckboxes(); // inicial

    // Estado (igual que en único)
    const divEstado = document.createElement("div");
    divEstado.classList.add("flex-column", "gap-2", "text-start");
    const labelEstado = document.createElement("label");
    const selectEstado = document.createElement("select");
    const si = document.createElement("option");
    const no = document.createElement("option");
    labelEstado.textContent = "Estado:";
    labelEstado.classList.add("form-label");
    selectEstado.classList.add("form-control");
    selectEstado.name = "estado";
    si.textContent = "Pagado";
    si.value = "Pagado";
    no.textContent = "No pagado";
    no.value = "No pagado";
    selectEstado.append(si, no);
    divEstado.append(labelEstado, selectEstado);

    // Cantidad
    const divCantidad = document.createElement("div");
    divCantidad.classList.add("flex-column", "gap-2", "text-start");
    const labelCantidad = document.createElement("label");
    const inputCantidad = document.createElement("input");
    labelCantidad.textContent = "Cantidad:";
    labelCantidad.classList.add("form-label");
    inputCantidad.classList.add("form-control");
    inputCantidad.name = "cantidad";
    inputCantidad.type = "number";
    inputCantidad.placeholder = "Ej: 100";
    inputCantidad.step = "0.01";
    inputCantidad.min = "0";
    divCantidad.append(labelCantidad, inputCantidad);

    // Botones
    const divButtoms = document.createElement("div");
    divButtoms.classList.add("flex-column", "gap-2", "mt-3");
    const btnAceptar = document.createElement("button");
    const btnCerrar = document.createElement("button");
    btnAceptar.textContent = "Crear recibos";
    btnAceptar.type = "submit";
    btnCerrar.textContent = "Cancelar";
    [btnAceptar, btnCerrar].forEach(btn => {
      btn.style.cssText = `padding: 10px 20px; margin: 5px; border: none; border-radius: 5px; cursor: pointer;`;
    });
    btnAceptar.style.background = "#4CAF50";
    btnAceptar.style.color = "white";
    btnCerrar.style.background = "#f44336";
    btnCerrar.style.color = "white";
    divButtoms.append(btnAceptar, btnCerrar);

    form.append(divCurso, divCheckboxes, divEstado, divCantidad, divButtoms);

    btnAceptar.addEventListener("click", (e) => {
      e.preventDefault();
      form.submit();
    });
    btnCerrar.addEventListener("click", cerrarDialogo);
    return form;
  };

  // --- Cambio entre pestañas ---
  let currentForm = buildUnicoForm();
  formContainer.appendChild(currentForm);

  unico.addEventListener("click", () => {
    if (unico.classList.contains("reciboCrearSeleccionado")) return; // ya está seleccionado

    varios.classList.add("reciboCrearNoSeleccionado");
    varios.classList.remove("reciboCrearSeleccionado");
    unico.classList.add("reciboCrearSeleccionado");
    unico.classList.remove("reciboCrearNoSeleccionado");

    formContainer.innerHTML = "";
    currentForm = buildUnicoForm();
    formContainer.appendChild(currentForm);
  });

  varios.addEventListener("click", () => {
    if (varios.classList.contains("reciboCrearSeleccionado")) return;

    varios.classList.add("reciboCrearSeleccionado");
    varios.classList.remove("reciboCrearNoSeleccionado");
    unico.classList.add("reciboCrearNoSeleccionado");
    unico.classList.remove("reciboCrearSeleccionado");

    formContainer.innerHTML = "";
    currentForm = buildVariosForm();
    formContainer.appendChild(currentForm);
  });

  dialog.append(divFlex);
  document.body.append(dialog);
  dialog.showModal();
};




// Funcion de editar para los recibos
const editarRecibo = (e) =>{

  if(e.target.tagName != "BUTTOM" && !e.target.classList.contains("btnEditarCurso") && !e.target.classList.contains("bi-pen-fill")) return;

  const trs = document.querySelectorAll(`[data-id]`);
  
  let trSeleccionado;

  for (const tr of trs) {
    if(tr.getAttribute("data-id") == e.target.id){
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

  const h2 = document.createElement("h2");
  h2.textContent = `Editar recibo de ${trSeleccionado[3].textContent} ${trSeleccionado[4].textContent}`;
  h2.style.marginBottom = "20px";
  h2.style.color = "black";

  const form = document.createElement("form");
  form.classList.add("text-black");
  form.action = `/forexfalcon/recibosTabla`;
  form.method = "post";

  const divID = document.createElement("div");
  divID.classList.add("flex-column");
  divID.classList.add("gap-2");
  divID.classList.add("text-start");
  
  const labelID = document.createElement("label");
  const inputID = document.createElement("input");
  const inputIDHidden = document.createElement("input");

  labelID.textContent = "ID:";
  labelID.classList.add("form-label");

  inputID.value = trSeleccionado[0].textContent;
  inputID.classList.add("form-control");
  inputID.disabled = true;
  inputID.maxLength = 10;

  inputIDHidden.type = "hidden";
  inputIDHidden.name = "updateID";
  inputIDHidden.value = trSeleccionado[0].textContent;

  divID.append(labelID,inputID,inputIDHidden);
  
  const divDNI = document.createElement("div");
  divDNI.classList.add("flex-column");
  divDNI.classList.add("gap-2");
  divDNI.classList.add("text-start");

  const labelDNI = document.createElement("label");
  const inputDNI = document.createElement("input");
  
  labelDNI.textContent = "DNI:";
  labelDNI.classList.add("form-label");

  inputDNI.disabled = true;
  inputDNI.classList.add("form-control");
  inputDNI.name = "updateDNI";
  inputDNI.type = "text";
  inputDNI.value = trSeleccionado[1].textContent;

  divDNI.append(labelDNI,inputDNI);

  const divClaveCurso = document.createElement("div");
  divClaveCurso.classList.add("flex-column");
  divClaveCurso.classList.add("gap-2");
  divClaveCurso.classList.add("text-start");

  const labelClaveCurso = document.createElement("label");
  const inputClaveCurso = document.createElement("input");
  
  labelClaveCurso.textContent = "Clave del curso:";
  labelClaveCurso.classList.add("form-label");

  inputClaveCurso.disabled = true;
  inputClaveCurso.classList.add("form-control");
  inputClaveCurso.name = "updateClaveCurso";
  inputClaveCurso.type = "text";
  inputClaveCurso.value = trSeleccionado[2].textContent;

  divClaveCurso.append(labelClaveCurso,inputClaveCurso);

  const divEstado = document.createElement("div");
  divEstado.classList.add("flex-column");
  divEstado.classList.add("gap-2");
  divEstado.classList.add("text-start");

  const labelEstado = document.createElement("label");
  const selectEstado = document.createElement("select");
  const si = document.createElement("option");
  const no = document.createElement("option");
  
  labelEstado.textContent = "Estado:";
  labelEstado.classList.add("form-label");

  selectEstado.classList.add("form-control");
  selectEstado.name = "updateEstado";

  si.textContent = trSeleccionado[6].textContent;
  si.value = trSeleccionado[6].textContent;
  
  if(si.value == "No pagado"){
    no.textContent = "Pagado";
    no.value = "Pagado"
  } else {
    no.textContent = "No pagado";
    no.value = "No pagado"
  }

  selectEstado.append(si,no);
  divEstado.append(labelEstado,selectEstado);

  const divCantidad = document.createElement("div");
  divCantidad.classList.add("flex-column");
  divCantidad.classList.add("gap-2");
  divCantidad.classList.add("text-start");

  const labelCantidad = document.createElement("label");
  const inputCantidad = document.createElement("input");
  
  labelCantidad.textContent = "Cantidad:";
  labelCantidad.classList.add("form-label");

  inputCantidad.classList.add("form-control");
  inputCantidad.name = "updateCantidad";
  inputCantidad.type = "text";
  inputCantidad.value = trSeleccionado[7].textContent;

  divCantidad.append(labelCantidad,inputCantidad);

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

  form.append(divID,divDNI,divClaveCurso,divEstado,divCantidad,divButtoms);
  
  btnAceptar.addEventListener("click", () => {
    const trs = document.querySelectorAll(`[data-id]`);
  
    let trSeleccionado;

    for (const tr of trs) {
      if(tr.getAttribute("data-id") == e.target.id){
        trSeleccionado = tr.children;
      }
    }

    inputIDHidden.value = trSeleccionado.getAttribute("data-id");
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





// Funcion de delete para los recibos
const deleteRecibo = (e) =>{

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

  const h2 = document.createElement("h2");
  h2.textContent = "¿Quieres borrar el recibo?";
  h2.style.marginBottom = "20px";
  h2.style.color = "black";

  const form = document.createElement("form");
  form.action = `/forexfalcon/recibosTabla`;
  form.method = "post";

  const inputID = document.createElement("input");
  inputID.type = "hidden";
  inputID.name = "deleteID";

  const btnAceptar = document.createElement("button");
  const btnCerrar = document.createElement("button");
  btnAceptar.textContent = "Borrar recibo";
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

  form.append(inputID,btnAceptar, btnCerrar);
  dialog.append(h2, form);

  btnAceptar.addEventListener("click", () => {
    const trs = document.querySelectorAll(`[data-id]`);
  
    let trSeleccionado;

    for (const tr of trs) {
      if(tr.getAttribute("data-id") == e.target.id){
        trSeleccionado = tr;
      }
    }

    inputID.value = trSeleccionado.getAttribute("data-id");
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

// Llamadas para las funciones
const tablaRecibos = document.querySelector("#tablaRecibos");

document.querySelector("#btnGenerarRecibos").addEventListener("click", crearRecibo);
tablaRecibos.addEventListener("click", editarRecibo);
tablaRecibos.addEventListener("click", deleteRecibo);

// Jquery - DataTable
$(document).ready(function() {
  var tablaRecibos;
  if ($('#tablaRecibos').length > 0) {
    tablaRecibos = $('#tablaRecibos').DataTable({
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