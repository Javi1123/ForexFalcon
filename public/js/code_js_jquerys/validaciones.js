"user strict";

////////////////////////
// Functions exportadas
////////////////////////

export const validacionesSugerencias = e =>{

  e.preventDefault();
  
  // Pillamos el formulario entero
  const formData = new FormData(formulario);

  let errores = [];
  
  const nombre = formData.get("nombre");
  const apellido = formData.get("apellido");
  const email = formData.get("email");
  const telefono = formData.get("telefono");
  const sugerencia = formData.get("sugerencia");

  // Validar nombre
  if (nombre) {
    const labelNombre = document.querySelector("#labelNombre");
    if (nombre.length > 15 || !/^(([a-zA-ZáéíóúÁÉÍÓÚñÑ\s])+){3,}$/.test(nombre)) {

      labelNombre.classList.add("red");

      errores.push({
        campo: "nombre",
        icon: "error",
        title: "Nombre no válido:",
        text: "Por favor, revise si su nombre contiene números o está vacío"
      });
    } else {
      labelNombre.classList.remove("red");
    }
  }

  // Validar apellido
  if (apellido) {
    const labelApellido = document.querySelector("#labelApellido");
    if (apellido.length > 25 || !/^(([a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+){3,})?$/.test(apellido)) {
      labelApellido.classList.add("red");
      
      errores.push({
        campo: "apellido",
        icon: "error",
        title: "Apellido no válido:",
        text: "Por favor, revise si su apellido contiene números o está vacío"
      });
    } else {
      labelApellido.classList.remove("red");
    }
  }

  // Validar email
  if (email) {
    const labelEmail = document.querySelector("#labelEmail");
    if (!/^([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})?$/.test(email)) {

      labelEmail.classList.add("red");
      
      errores.push({
        campo: "email",
        icon: "error",
        title: "Email no válido:",
        text: "Por favor, revise si su email está mal escrito"
      });
    } else {
      labelEmail.classList.remove("red");
    }
  }
  
  // Validar teléfono
  if (telefono) {
    const labelTelefono = document.querySelector("#labelTelefono");
    if (!/^([0-9]{9})?$/.test(telefono)) {

      labelTelefono.classList.add("red");
      
      errores.push({
        campo: "telefono",
        icon: "error",
        title: "Teléfono no válido:",
        text: "Por favor, revise si su número de teléfono es válido (9 dígitos)"
      });
    } else {
      labelTelefono.classList.remove("red");
    }
  }
  
  // Validar intereses
  if (sugerencia) {

    let valorLimpio = quitarEtiquetasHTML(sugerencia);
    const labelSugerencia = document.querySelector("#labelSugerencia");
    
    if (valorLimpio == "" || valorLimpio.length < 10) {

      labelSugerencia.classList.add("red");
      
      errores.push({
        campo: "interes",
        icon: "error",
        title: "Intereses poco extensos:",
        text: "Por favor, asegúrese de que sus intereses contengan más de 10 caracteres"
      });
    } else {
      labelSugerencia.classList.remove("red");
    }
  }


  if(errores.length == 1){
    popErrores(errores[0].icon, errores[0].title, errores[0].text);
  } else if (errores.length > 1){
    popErrores(
      "error",
      "Datos de Entrada Erróneos:",
      "Por favor, revise los datos e introdúzcalos correctamente"
    );
  } else if(errores.length == 0) {
    formulario.submit();
  }

}





export const validacionesFormacion = e =>{

  e.preventDefault();

  // Pillamos el formulario entero
  const formData = new FormData(formulario);

  let errores = [];

  const documento = formData.get("documento");
  const DNI = formData.get("DNI");
  const nombre = formData.get("nombre");
  const apellido = formData.get("apellido");
  const email = formData.get("email");
  const telefono = formData.get("telefono");
  const direccion = formData.get("direccion");
  const interes = formData.get("interes");

  // Validar DNI
  if(documento && DNI){
    const labelDNI = document.querySelector("#labelDNI");
    if(documento == "NIF"){
      if(!/^[0-9]{8}[A-Z]+$/.test(DNI)){
        errores.push({
          campo: "DNI",
          icon: "error",
          title: "NIF no valido:",
          text: "Por favor, revise si su NIF tiene los caracteres basicos"
        });
        labelDNI.classList.add("red");
      }
    } else if(documento == "NIE"){
      if(!/^[XYZxyz][0-9]{7,8}[A-Za-z]$/.test(DNI)){
        errores.push({
          campo: "DNI",
          icon: "error",
          title: "NIE no válido:",
          text: "Por favor, revise si su NIE tiene los caracteres basicos"
        });
        labelDNI.classList.add("red");
      }
    } else if(documento == "pasaporte"){
      if(!/^[A-Za-z]{1,2}[0-9]{6,9}$/.test(DNI)){
        errores.push({
          campo: "DNI",
          icon: "error",
          title: "Pasaporte no valido:",
          text: "Por favor, revise si su pasaporte tiene los caracteres basicos"
        });
        labelDNI.classList.add("red");
      }
    } else {
      labelDNI.classList.remove("red");
    }
  }

  // Validar nombre
  if (nombre) {
    const labelNombre = document.querySelector("#labelNombre");
    if (nombre.length > 15 || !/^(([a-zA-ZáéíóúÁÉÍÓÚñÑ\s])+){3,}$/.test(nombre)) {

      labelNombre.classList.add("red");

      errores.push({
        campo: "nombre",
        icon: "error",
        title: "Nombre no válido:",
        text: "Por favor, revise si su nombre contiene números o está vacío"
      });
    } else {
      labelNombre.classList.remove("red");
    }
  }

  // Validar apellido
  if (apellido) {
    const labelApellido = document.querySelector("#labelApellido");
    if (apellido.length > 25 || !/^([a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+){3,}$/.test(apellido)) {

      labelApellido.classList.add("red");
      
      errores.push({
        campo: "apellido",
        icon: "error",
        title: "Apellido no válido:",
        text: "Por favor, revise si su apellido contiene números o está vacío"
      });
    } else {
      labelApellido.classList.remove("red");
    }
  }

  // Validar email
  if (email) {
    const labelEmail = document.querySelector("#labelEmail");
    if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email)) {

      labelEmail.classList.add("red");
      
      errores.push({
        campo: "email",
        icon: "error",
        title: "Email no válido:",
        text: "Por favor, revise si su email está mal escrito"
      });
    } else {
      labelEmail.classList.remove("red");
    }
  }
  
  // Validar teléfono
  if (telefono) {
    const labelTelefono = document.querySelector("#labelTelefono");
    if (!/^[0-9]{9}$/.test(telefono)) {

      labelTelefono.classList.add("red");
      
      errores.push({
        campo: "telefono",
        icon: "error",
        title: "Teléfono no válido:",
        text: "Por favor, revise si su número de teléfono es válido (9 dígitos)"
      });
    } else {
      labelTelefono.classList.remove("red");
    }
  }

  if(direccion){
    const labelDireccion = document.querySelector("#labelDireccion");
     if (!/^C:\s*[^,]+(?:,\s*Nº\s*[^,]+)?(?:,\s*Piso\s*[^,]+)?(?:,\s*Puerta\s*[^,]+)?$/i.test(direccion)) {

      labelDireccion.classList.add("red");
      
      errores.push({
        campo: "direccion",
        icon: "error",
        title: "La direccion no esta bien escrita:",
        text: "Por favor, mire el siguinte ejemplo C: Valle de Lora, Nº 1"
      });
    } else {
      labelDireccion.classList.remove("red");
    }
  }


  // Validar intereses
  if (interes) {

    let valorLimpio = quitarEtiquetasHTML(interes);
    const labelInteres = document.querySelector("#labelInteres");
    
    if (valorLimpio == "" || valorLimpio.length < 10) {

      labelInteres.classList.add("red");
      
      errores.push({
        campo: "interes",
        icon: "error",
        title: "Intereses poco extensos:",
        text: "Por favor, asegúrese de que sus intereses contengan más de 10 caracteres"
      });
    } else {
      labelInteres.classList.remove("red");
    }
  }

  if(errores.length == 1){
    popErrores(errores[0].icon, errores[0].title, errores[0].text);
  } else if (errores.length > 1){
    popErrores(
      "error",
      "Datos de Entrada Erróneos:",
      "Por favor, revise los datos e introdúzcalos correctamente"
    );
  } else if(errores.length == 0) {
    formulario.submit();
  }

}



////////////////////////
// Functions 
////////////////////////

const quitarEtiquetasHTML = (html) => {
  const doc = new DOMParser().parseFromString(html, 'text/html');
  return doc.body.textContent || "";
}

const popErrores = (icon,title,text) =>{
  Swal.fire({
    icon: icon,
    title: title,
    text: text,
  });
}

