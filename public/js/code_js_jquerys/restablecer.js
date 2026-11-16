"use strict";

// Página "Crea tu nueva contraseña": requisitos en directo (mismos que el registro) y ojo para ver la contraseña.
// El servidor vuelve a comprobarlo todo; esto solo sirve para guiar al usuario mientras escribe.

const REGLAS = [
  { texto: "Mínimo 8 caracteres",               ok: (p) => p.length >= 8 },
  { texto: "Una letra mayúscula (A-Z)",         ok: (p) => /[A-Z]/.test(p) },
  { texto: "Una letra minúscula (a-z)",         ok: (p) => /[a-z]/.test(p) },
  { texto: "Un número (0-9)",                   ok: (p) => /[0-9]/.test(p) },
  { texto: "Un carácter especial (!@#$%&*...)", ok: (p) => /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(p) },
  { texto: "Sin espacios",                      ok: (p) => !/\s/.test(p) },
  { texto: "Máximo 64 caracteres",              ok: (p) => p.length <= 64 },
];

document.addEventListener("DOMContentLoaded", () => {
  const form    = document.getElementById("formRestablecer");
  const pass    = document.getElementById("regPassword");
  const confirm = document.getElementById("regPasswordConfirm");
  const caja    = document.getElementById("reglasContrasena");
  const titulo  = document.getElementById("reglasTitulo");
  const lista   = document.getElementById("reglasLista");
  if (!form || !pass || !confirm || !caja || !titulo || !lista) return;

  let pintadas = new Set();

  const pendientes = () => {
    const p = pass.value, c = confirm.value;
    const faltan = REGLAS.filter((r) => !r.ok(p)).map((r) => r.texto);
    if (c !== p || (p === "" && c === "")) {
      faltan.push(p !== "" && c === "" ? "Repite la contraseña en «Confirmar contraseña»" : "Las dos contraseñas deben ser iguales");
    }
    return faltan;
  };

  const actualizar = () => {
    const vacio  = pass.value === "" && confirm.value === "";
    const faltan = pendientes();
    const completa = faltan.length === 0;

    caja.classList.remove("d-none");
    caja.classList.toggle("ff-reglas-ok", completa);
    titulo.textContent = completa
      ? "¡Contraseña válida! Ya puedes guardarla."
      : vacio ? "Tu contraseña debe tener:"
      : (faltan.length === 1 ? "Te falta 1 requisito:" : `Te faltan ${faltan.length} requisitos:`);

    lista.replaceChildren();
    const nuevas = new Set();
    for (const texto of faltan) {
      const li = document.createElement("li");
      li.textContent = texto;
      if (!pintadas.has(texto)) li.classList.add("ff-regla-nueva");
      lista.appendChild(li);
      nuevas.add(texto);
    }
    pintadas = nuevas;
  };

  pass.addEventListener("input", actualizar);
  confirm.addEventListener("input", actualizar);
  actualizar();

  // No se envía si falta algo (el servidor lo comprobaría igualmente)
  form.addEventListener("submit", (e) => {
    if (pendientes().length > 0) {
      e.preventDefault();
      actualizar();
      pass.focus();
      return;
    }
    const b = document.getElementById("btnRestablecer");
    b.disabled = true;
    b.querySelector("span").textContent = "Guardando...";
  });

  // Ojo de mostrar/ocultar. Font Awesome sustituye cada <i> por un <svg>, así que el icono se crea de nuevo en lugar de cambiarle la clase.
  for (const ojo of document.querySelectorAll(".ff-ojo")) {
    const alternar = () => {
      const input = document.getElementById(ojo.dataset.ojo);
      const visible = input.type === "text";
      input.type = visible ? "password" : "text";
      const i = document.createElement("i");
      i.className = "fa-solid " + (visible ? "fa-eye" : "fa-eye-slash");
      ojo.replaceChildren(i);
    };
    ojo.addEventListener("click", alternar);
    ojo.addEventListener("keydown", (e) => {
      if (e.key === "Enter" || e.key === " ") { e.preventDefault(); alternar(); }
    });
  }
});
