"user strict";

////////////////////////
// Functions exportadas
////////////////////////

export const validacionesInicioSesion = e => {
  e.preventDefault();

  let formulario = e.target;

  const formData = new FormData(formulario);

  let errores = false;

  // Valores formulario
  const email      = formData.get("email_login");
  const contraseña = formData.get("contraseña_login");

  // Campo errores
  const errorEmail      = document.querySelector("#errorEmail");
  const errorContraseña = document.querySelector("#errorContraseña");

  // Valores del formulario validados
  const resultEmail      = validarEmail(email);
  const resultContraseña = validarContraseña(contraseña);

  if (!resultEmail.ok) {
    errorEmail.textContent = resultEmail.msg;
    errorEmail.classList.remove("d-none");
    errores = true;
  }

  if (!resultContraseña.ok) {
    errorContraseña.textContent = resultContraseña.msg;
    errorContraseña.classList.remove("d-none");
    errores = true;
  }

  if (errores) {
    return false;
  } else {
    return true;
  }
}




export const validacionesRegistrarse = e =>{
  e.preventDefault();

  let formulario = e.target;

  const formData = new FormData(formulario);

  let errores = false;

  // Valores formulario
  const nombre               = formData.get("nombre_registro");
  const apellido             = formData.get("apellido_registro");
  const email                = formData.get("email_registro");
  const telefono             = formData.get("telefono_registro");
  const pais                 = formData.get("pais_registro");
  const fecha_nacimiento     = formData.get("fecha_nacimiento_registro");
  const contraseña           = formData.get("contraseña_registro");
  const confirmar_contraseña = formData.get("confirmar_contraseña_registro");

  // Campo errores
  const errorNombreRegistro             = document.querySelector("#errorNombreRegistro");
  const errorApellidoRegistro           = document.querySelector("#errorApellidoRegistro");
  const errorEmailRegistro              = document.querySelector("#errorEmailRegistro");
  const errorTelefonoRegistro           = document.querySelector("#errorTelefonoRegistro");
  const errorPaisRegistro               = document.querySelector("#errorPaisRegistro");
  const errorFechaRegistro              = document.querySelector("#errorFechaRegistro");
  const errorContraseñaRegistro         = document.querySelector("#errorContraseñaRegistro");
  const errorConfirmaContraseñaRegistro = document.querySelector("#errorConfirmaContraseñaRegistro");

  // Valores del formulario validados
  const resultNombre              = validarNombre(nombre);
  const resultApellido            = validarApellido(apellido);
  const resultEmail               = validarEmail(email);
  const resultTelefono            = validarTelefono(telefono);
  const resultPais                = validarPais(pais);
  const resultFecha               = validarFechaNacimiento(fecha_nacimiento);
  const resultContraseña          = validarContraseña(contraseña);
  const resultConfirmarContraseña = validarConfirmarContraseña(contraseña,confirmar_contraseña);

  if (!resultNombre.ok) {
    errorNombreRegistro.textContent = resultNombre.msg;
    errorNombreRegistro.classList.remove("d-none");
    errores = true;
  }

  if (!resultApellido.ok) {
    errorApellidoRegistro.textContent = resultApellido.msg;
    errorApellidoRegistro.classList.remove("d-none");
    errores = true;
  }

  if (!resultEmail.ok) {
    errorEmailRegistro.textContent = resultEmail.msg;
    errorEmailRegistro.classList.remove("d-none");
    errores = true;
  }

  if (!resultTelefono.ok) {
    errorTelefonoRegistro.textContent = resultTelefono.msg;
    errorTelefonoRegistro.classList.remove("d-none");
    errores = true;
  }

  if (!resultPais.ok) {
    errorPaisRegistro.textContent = resultPais.msg;
    errorPaisRegistro.classList.remove("d-none");
    errores = true;
  }

  if (!resultFecha.ok) {
    errorFechaRegistro.textContent = resultFecha.msg;
    errorFechaRegistro.classList.remove("d-none");
    errores = true;
  }

  if (!resultContraseña.ok) {
    errorContraseñaRegistro.textContent = resultContraseña.msg;
    errorContraseñaRegistro.classList.remove("d-none");
    errores = true;
  }

  if (!resultConfirmarContraseña.ok) {
    errorConfirmaContraseñaRegistro.textContent = resultConfirmarContraseña.msg;
    errorConfirmaContraseñaRegistro.classList.remove("d-none");
    errores = true;
  }

  if (errores) {
    return false;
  } else {
    return true;
  }
}



////////////////////////
// Functions 
////////////////////////

// ── Nombre y Apellido
const regexNombre = /^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ'\-\s]+$/;

const validarNombre = (nombre) => {
  if (!nombre || nombre.trim() === "")   return { ok: false, msg: "El nombre es obligatorio" };
  const valor = nombre.trim();
  if (valor.length < 2)                  return { ok: false, msg: "Mínimo 2 caracteres" };
  if (valor.length > 50)                 return { ok: false, msg: "Máximo 50 caracteres" };
  if (!regexNombre.test(valor))          return { ok: false, msg: "El nombre solo puede contener letras" };
  if (/\s{2,}/.test(valor))              return { ok: false, msg: "No puede contener espacios dobles" };
  return { ok: true };
};

const validarApellido = (apellido) => {
  if (!apellido || apellido.trim() === "")   return { ok: false, msg: "El apellido es obligatorio" };
  const valor = apellido.trim();
  if (valor.length < 2)                      return { ok: false, msg: "Mínimo 2 caracteres" };
  if (valor.length > 50)                     return { ok: false, msg: "Máximo 50 caracteres" };
  if (!regexNombre.test(valor))              return { ok: false, msg: "El apellido solo puede contener letras" };
  if (/\s{2,}/.test(valor))                  return { ok: false, msg: "No puede contener espacios dobles" };
  return { ok: true };
};

// ── Email 
const regexEmail = /^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/;

const validarEmail = (email) => {
  if (!email || email.trim() === "")      return { ok: false, msg: "El email es obligatorio" };
  if (!regexEmail.test(email.trim()))     return { ok: false, msg: "El email no es válido" };
  if (email.length > 254)                 return { ok: false, msg: "El email es demasiado largo" };
  return { ok: true };
};

// ── Contraseña 
const regexMayuscula  = /[A-Z]/;
const regexMinuscula  = /[a-z]/;
const regexNumero     = /[0-9]/;
const regexEspecial   = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;

const validarContraseña = (pass) => {
  if (!pass || pass.trim() === "")        return { ok: false, msg: "La contraseña es obligatoria" };
  if (pass.length < 8)                    return { ok: false, msg: "Mínimo 8 caracteres" };
  if (pass.length > 64)                   return { ok: false, msg: "Máximo 64 caracteres" };
  if (!regexMayuscula.test(pass))         return { ok: false, msg: "Debe contener al menos una mayúscula" };
  if (!regexMinuscula.test(pass))         return { ok: false, msg: "Debe contener al menos una minúscula" };
  if (!regexNumero.test(pass))            return { ok: false, msg: "Debe contener al menos un número" };
  if (!regexEspecial.test(pass))          return { ok: false, msg: "Debe contener al menos un carácter especial (!@#$...)" };
  if (/\s/.test(pass))                    return { ok: false, msg: "No puede contener espacios" };
  return { ok: true };
};

const validarConfirmarContraseña = (pass, c_pass) => {
  if (!c_pass || c_pass.trim() === "")        return { ok: false, msg: "La contraseña es obligatoria" };
  if (c_pass.trim() != pass.trim())        return { ok: false, msg: "La contraseña no es igual" };
  return { ok: true };
};

// ── Telefono
const validarTelefono = (tel) => {
  if (!tel || tel.trim() === "")        return { ok: false, msg: "El teléfono es obligatorio" };
  if (!/^\d+$/.test(tel))               return { ok: false, msg: "Solo se permiten dígitos" };
  if (tel.length < 9)                   return { ok: false, msg: "Mínimo 9 dígitos" };
  if (tel.length > 15)                  return { ok: false, msg: "Máximo 15 dígitos (estándar E.164)" };
  return { ok: true };
};

// ── Pais
const PAISES_VALIDOS = new Set([
  "Afghanistan","Albania","Algeria","Andorra","Angola","Antigua and Barbuda",
  "Argentina","Armenia","Australia","Austria","Azerbaijan","Bahamas","Bahrain",
  "Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bhutan","Bolivia",
  "Bosnia and Herzegovina","Botswana","Brazil","Brunei","Bulgaria","Burkina Faso",
  "Burundi","Cabo Verde","Cambodia","Cameroon","Canada","Central African Republic",
  "Chad","Chile","China","Colombia","Comoros","Congo (Congo-Brazzaville)",
  "Costa Rica","Croatia","Cuba","Cyprus","Czechia","Denmark","Djibouti","Dominica",
  "Dominican Republic","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea",
  "Estonia","Eswatini","Ethiopia","Fiji","Finland","France","Gabon","Gambia",
  "Georgia","Germany","Ghana","Greece","Grenada","Guatemala","Guinea",
  "Guinea-Bissau","Guyana","Haiti","Honduras","Hungary","Iceland","India",
  "Indonesia","Iran","Iraq","Ireland","Israel","Italy","Jamaica","Japan","Jordan",
  "Kazakhstan","Kenya","Kiribati","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon",
  "Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Madagascar",
  "Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Mauritania",
  "Mauritius","Mexico","Micronesia","Moldova","Monaco","Mongolia","Montenegro",
  "Morocco","Mozambique","Myanmar","Namibia","Nauru","Nepal","Netherlands",
  "New Zealand","Nicaragua","Niger","Nigeria","North Korea","North Macedonia",
  "Norway","Oman","Pakistan","Palau","Palestine","Panama","Papua New Guinea",
  "Paraguay","Peru","Philippines","Poland","Portugal","Qatar","Romania","Russia",
  "Rwanda","Saint Kitts and Nevis","Saint Lucia","Saint Vincent and the Grenadines",
  "Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Serbia",
  "Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands",
  "Somalia","South Africa","South Korea","South Sudan","Spain","Sri Lanka","Sudan",
  "Suriname","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania",
  "Thailand","Timor-Leste","Togo","Tonga","Trinidad and Tobago","Tunisia","Turkey",
  "Turkmenistan","Tuvalu","Uganda","Ukraine","United Arab Emirates","United Kingdom",
  "United States","Uruguay","Uzbekistan","Vanuatu","Vatican City","Venezuela",
  "Vietnam","Yemen","Zambia","Zimbabwe"
]);

const validarPais = (pais) => {
  if (!pais || pais.trim() === "") return { ok: false, msg: "El país es obligatorio" };
  if (!PAISES_VALIDOS.has(pais.trim()))    return { ok: false, msg: "Selecciona un país válido" };
  return { ok: true };
};

// ── Fecha de nacimineto
const validarFechaNacimiento = (fecha) => {
  if (!fecha || fecha.trim() === "")  return { ok: false, msg: "La fecha de nacimiento es obligatoria" };

  const fechaNac  = new Date(fecha);
  if (isNaN(fechaNac.getTime()))      return { ok: false, msg: "La fecha no es válida" };

  const hoy       = new Date();
  const mayorEdad = new Date(fechaNac);
  mayorEdad.setFullYear(mayorEdad.getFullYear() + 18);

  if (mayorEdad > hoy)                return { ok: false, msg: "Debes ser mayor de 18 años" };

  const hace120   = new Date();
  hace120.setFullYear(hoy.getFullYear() - 120);
  if (fechaNac < hace120)             return { ok: false, msg: "La fecha introducida no es válida" };

  return { ok: true };
};