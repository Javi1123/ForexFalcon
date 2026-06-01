"user strict";

////////////////////////
// Functions exportadas
////////////////////////

export const validacionesInicioSesion = e => {
  e.preventDefault();

  let formulario = e.target;

  const formData = new FormData(formulario);

  let errores = false;

  const email      = formData.get("email");
  const contraseña = formData.get("contraseña");

  const emailVacio      = document.querySelector("#emailVacio");
  const errorEmail      = document.querySelector("#errorEmail");
  const contraseñaVacio = document.querySelector("#contraseñaVacio");
  const errorContraseña = document.querySelector("#errorContraseña");

  formulario.querySelector('[name="email"]').addEventListener('input', () => {
    emailVacio.classList.add('d-none');
    errorEmail.classList.add('d-none');
  });

  formulario.querySelector('[name="contraseña"]').addEventListener('input', () => {
    contraseñaVacio.classList.add('d-none');
    errorContraseña.classList.add('d-none');
  });

  const resultEmail      = validarEmail(email);
  const resultContraseña = validarContraseña(contraseña);

  if (!resultEmail.ok) {
    emailVacio.textContent = resultEmail.msg;
    emailVacio.classList.remove("d-none");
    errores = true;
  }

  if (!resultContraseña.ok) {
    contraseñaVacio.textContent = resultContraseña.msg;
    contraseñaVacio.classList.remove("d-none");
    errores = true;
  }

  if (errores) {
    return false;
  } else {
    return true;
  }
}




export const validacionesFormacion = e =>{
  e.preventDefault();

  let formulario = e.target;


}



////////////////////////
// Functions 
////////////////////////

// ── Email ────────────────────────────────────────────────
const regexEmail = /^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/;

const validarEmail = (email) => {
  if (!email || email.trim() === "")      return { ok: false, msg: "El email es obligatorio" };
  if (!regexEmail.test(email.trim()))     return { ok: false, msg: "El email no es válido" };
  if (email.length > 254)                 return { ok: false, msg: "El email es demasiado largo" };
  return { ok: true };
};

// ── Contraseña ───────────────────────────────────────────
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