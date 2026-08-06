"user strict";

///////////////////
// Functions
///////////////////

const validaciones = async () => {
  const formularioInicio = document.querySelector('form[action*="tipo=inicio"]')
  const formularioRegistro = document.querySelector('form[action*="tipo=registro"]')

  const btnInicioSesion = document.querySelector("#btnInicioSesion");
  const btnRegistrarse = document.querySelector("#btnRegistrarse");

  const { validacionesInicioSesion, validacionesRegistrarse } = await import("./validaciones.js");

  formularioInicio.addEventListener("submit", (e) => {
    if (validacionesInicioSesion(e)) {
      btnInicioSesion.textContent = "Comprobando...";
      btnInicioSesion.disabled = true;
      btnInicioSesion.classList.add("text-white");
      formularioInicio.submit();
    }
  });

  formularioRegistro.addEventListener("submit", (e) => {
    if (validacionesRegistrarse(e)) {
      btnRegistrarse.textContent = "Comprobando...";
      btnRegistrarse.disabled = true;
      btnRegistrarse.classList.add("text-white");
      formularioRegistro.submit();
    }
  });

};

// Cambio de pestaña en el login / registrarse
const ffClearPanel = (panelId) => {
  const panel = document.querySelector(`#${panelId}`);

  for (const el of panel.querySelectorAll("input, select, textarea")) {
    if (el.type === "checkbox" || el.type === "radio") {
      el.checked = false;
    } else {
      el.value = "";
    }
  }

  for (const el of panel.querySelectorAll(".is-invalid")) {
    el.classList.remove("is-invalid");
  }

  for (const el of panel.querySelectorAll(".invalid-feedback")) {
    el.textContent = "";
    el.classList.add("d-none");
  }
};

const ffShowTab = (targetId) => {
  for (const btn of document.querySelectorAll('.ff-tab-btn')) {
    btn.classList.toggle('ff-active', btn.dataset.ffTarget === targetId);
  }
  for (const panel of document.querySelectorAll('.ff-panel')) {
    panel.classList.toggle('d-none', panel.id !== targetId);
    if (panel.id !== targetId) ffClearPanel(panel.id);
  }
};


// Funcion para limpiar el error cuando se escriba otra vez
const limpiarError = (e) => {
  const campo = e.target;

  // Quitar el borde/estado rojo del input
  campo.classList.remove('is-invalid');

  // Buscar el contenedor (input-group o form-floating) y el div de error justo después
  const contenedor = campo.closest('.input-group') || campo.closest('.form-floating');
  if (!contenedor) return;

  const errorDiv = contenedor.nextElementSibling;
  if (errorDiv && errorDiv.id && errorDiv.id.startsWith('error')) {
    errorDiv.classList.add('d-none');
    errorDiv.textContent = ''; // por si el mensaje se inyecta dinámicamente
  }
}

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('formRegistro');
  if (!form) return;

  const steps = form.querySelectorAll('.ff-step');
  const dots = document.querySelectorAll('.ff-step-dot');

  function goToStep(stepNumber) {
    steps.forEach(step => {
      step.classList.toggle('d-none', step.dataset.step != stepNumber);
    });
    dots.forEach(dot => {
      const dotStep = Number(dot.dataset.step);
      dot.classList.toggle('active', dotStep === stepNumber);
      dot.classList.toggle('done', dotStep < stepNumber);
    });
  }

  // Botones de Atrás y Siguiente en Registro en form
  function validarPaso(stepNumber) {
    const step = form.querySelector(`.ff-step[data-step="${stepNumber}"]`);
    const campos = step.querySelectorAll('input[required], input:not([type=hidden]), select');
    let valido = true;

    campos.forEach(campo => {
      if (campo.hasAttribute('required') || campo.value.trim() !== '') {
        // Validación básica: campo vacío si es obligatorio
      }
      if (!campo.checkValidity()) {
        campo.classList.add('is-invalid');
        valido = false;
      } else {
        campo.classList.remove('is-invalid');
      }
    });

    return valido;
  }

  // Botones "Siguiente"
  form.querySelectorAll('.ff-next').forEach(btn => {
    btn.addEventListener('click', () => {
      const pasoActual = btn.closest('.ff-step').dataset.step;
      if (validarPaso(pasoActual)) {
        goToStep(Number(btn.dataset.next));
      }
    });
  });

  // Botones "Atrás"
  form.querySelectorAll('.ff-back').forEach(btn => {
    btn.addEventListener('click', () => {
      goToStep(Number(btn.dataset.back));
    });
  });

  // ── Prefijos telefónicos con emojis de bandera ────────────────────

  const PREFIJOS_PAIS = {
    "Afghanistan": "+93", "Albania": "+355", "Algeria": "+213", "Andorra": "+376", "Angola": "+244",
    "Antigua and Barbuda": "+1268", "Argentina": "+54", "Armenia": "+374", "Australia": "+61", "Austria": "+43",
    "Azerbaijan": "+994", "Bahamas": "+1242", "Bahrain": "+973", "Bangladesh": "+880", "Barbados": "+1246",
    "Belarus": "+375", "Belgium": "+32", "Belize": "+501", "Benin": "+229", "Bhutan": "+975",
    "Bolivia": "+591", "Bosnia and Herzegovina": "+387", "Botswana": "+267", "Brazil": "+55", "Brunei": "+673",
    "Bulgaria": "+359", "Burkina Faso": "+226", "Burundi": "+257", "Cabo Verde": "+238", "Cambodia": "+855",
    "Cameroon": "+237", "Canada": "+1", "Central African Republic": "+236", "Chad": "+235", "Chile": "+56",
    "China": "+86", "Colombia": "+57", "Comoros": "+269", "Congo (Congo-Brazzaville)": "+242", "Costa Rica": "+506",
    "Croatia": "+385", "Cuba": "+53", "Cyprus": "+357", "Czechia": "+420", "Denmark": "+45",
    "Djibouti": "+253", "Dominica": "+1767", "Dominican Republic": "+1809", "Ecuador": "+593", "Egypt": "+20",
    "El Salvador": "+503", "Equatorial Guinea": "+240", "Eritrea": "+291", "Estonia": "+372", "Eswatini": "+268",
    "Ethiopia": "+251", "Fiji": "+679", "Finland": "+358", "France": "+33", "Gabon": "+241",
    "Gambia": "+220", "Georgia": "+995", "Germany": "+49", "Ghana": "+233", "Greece": "+30",
    "Grenada": "+1473", "Guatemala": "+502", "Guinea": "+224", "Guinea-Bissau": "+245", "Guyana": "+592",
    "Haiti": "+509", "Honduras": "+504", "Hungary": "+36", "Iceland": "+354", "India": "+91",
    "Indonesia": "+62", "Iran": "+98", "Iraq": "+964", "Ireland": "+353", "Israel": "+972",
    "Italy": "+39", "Jamaica": "+1876", "Japan": "+81", "Jordan": "+962", "Kazakhstan": "+7",
    "Kenya": "+254", "Kiribati": "+686", "Kuwait": "+965", "Kyrgyzstan": "+996", "Laos": "+856",
    "Latvia": "+371", "Lebanon": "+961", "Lesotho": "+266", "Liberia": "+231", "Libya": "+218",
    "Liechtenstein": "+423", "Lithuania": "+370", "Luxembourg": "+352", "Madagascar": "+261", "Malawi": "+265",
    "Malaysia": "+60", "Maldives": "+960", "Mali": "+223", "Malta": "+356", "Marshall Islands": "+692",
    "Mauritania": "+222", "Mauritius": "+230", "Mexico": "+52", "Micronesia": "+691", "Moldova": "+373",
    "Monaco": "+377", "Mongolia": "+976", "Montenegro": "+382", "Morocco": "+212", "Mozambique": "+258",
    "Myanmar": "+95", "Namibia": "+264", "Nauru": "+674", "Nepal": "+977", "Netherlands": "+31",
    "New Zealand": "+64", "Nicaragua": "+505", "Niger": "+227", "Nigeria": "+234", "North Korea": "+850",
    "North Macedonia": "+389", "Norway": "+47", "Oman": "+968", "Pakistan": "+92", "Palau": "+680",
    "Palestine": "+970", "Panama": "+507", "Papua New Guinea": "+675", "Paraguay": "+595", "Peru": "+51",
    "Philippines": "+63", "Poland": "+48", "Portugal": "+351", "Qatar": "+974", "Romania": "+40",
    "Russia": "+7", "Rwanda": "+250", "Saint Kitts and Nevis": "+1869", "Saint Lucia": "+1758",
    "Saint Vincent and the Grenadines": "+1784", "Samoa": "+685", "San Marino": "+378",
    "Sao Tome and Principe": "+239", "Saudi Arabia": "+966", "Senegal": "+221", "Serbia": "+381",
    "Seychelles": "+248", "Sierra Leone": "+232", "Singapore": "+65", "Slovakia": "+421", "Slovenia": "+386",
    "Solomon Islands": "+677", "Somalia": "+252", "South Africa": "+27", "South Korea": "+82",
    "South Sudan": "+211", "Spain": "+34", "Sri Lanka": "+94", "Sudan": "+249", "Suriname": "+597",
    "Sweden": "+46", "Switzerland": "+41", "Syria": "+963", "Taiwan": "+886", "Tajikistan": "+992",
    "Tanzania": "+255", "Thailand": "+66", "Timor-Leste": "+670", "Togo": "+228", "Tonga": "+676",
    "Trinidad and Tobago": "+1868", "Tunisia": "+216", "Turkey": "+90", "Turkmenistan": "+993", "Tuvalu": "+688",
    "Uganda": "+256", "Ukraine": "+380", "United Arab Emirates": "+971", "United Kingdom": "+44",
    "United States": "+1", "Uruguay": "+598", "Uzbekistan": "+998", "Vanuatu": "+678", "Vatican City": "+379",
    "Venezuela": "+58", "Vietnam": "+84", "Yemen": "+967", "Zambia": "+260", "Zimbabwe": "+263"
  };

  // Mapeo de nombres de país a códigos ISO (para generar el emoji)
  const PAIS_TO_FLAG = {
    "Afghanistan": "AF", "Albania": "AL", "Algeria": "DZ", "Andorra": "AD", "Angola": "AO",
    "Antigua and Barbuda": "AG", "Argentina": "AR", "Armenia": "AM", "Australia": "AU", "Austria": "AT",
    "Azerbaijan": "AZ", "Bahamas": "BS", "Bahrain": "BH", "Bangladesh": "BD", "Barbados": "BB",
    "Belarus": "BY", "Belgium": "BE", "Belize": "BZ", "Benin": "BJ", "Bhutan": "BT",
    "Bolivia": "BO", "Bosnia and Herzegovina": "BA", "Botswana": "BW", "Brazil": "BR", "Brunei": "BN",
    "Bulgaria": "BG", "Burkina Faso": "BF", "Burundi": "BI", "Cabo Verde": "CV", "Cambodia": "KH",
    "Cameroon": "CM", "Canada": "CA", "Central African Republic": "CF", "Chad": "TD", "Chile": "CL",
    "China": "CN", "Colombia": "CO", "Comoros": "KM", "Congo (Congo-Brazzaville)": "CG", "Costa Rica": "CR",
    "Croatia": "HR", "Cuba": "CU", "Cyprus": "CY", "Czechia": "CZ", "Denmark": "DK",
    "Djibouti": "DJ", "Dominica": "DM", "Dominican Republic": "DO", "Ecuador": "EC", "Egypt": "EG",
    "El Salvador": "SV", "Equatorial Guinea": "GQ", "Eritrea": "ER", "Estonia": "EE", "Eswatini": "SZ",
    "Ethiopia": "ET", "Fiji": "FJ", "Finland": "FI", "France": "FR", "Gabon": "GA",
    "Gambia": "GM", "Georgia": "GE", "Germany": "DE", "Ghana": "GH", "Greece": "GR",
    "Grenada": "GD", "Guatemala": "GT", "Guinea": "GN", "Guinea-Bissau": "GW", "Guyana": "GY",
    "Haiti": "HT", "Honduras": "HN", "Hungary": "HU", "Iceland": "IS", "India": "IN",
    "Indonesia": "ID", "Iran": "IR", "Iraq": "IQ", "Ireland": "IE", "Israel": "IL",
    "Italy": "IT", "Jamaica": "JM", "Japan": "JP", "Jordan": "JO", "Kazakhstan": "KZ",
    "Kenya": "KE", "Kiribati": "KI", "Kuwait": "KW", "Kyrgyzstan": "KG", "Laos": "LA",
    "Latvia": "LV", "Lebanon": "LB", "Lesotho": "LS", "Liberia": "LR", "Libya": "LY",
    "Liechtenstein": "LI", "Lithuania": "LT", "Luxembourg": "LU", "Madagascar": "MG", "Malawi": "MW",
    "Malaysia": "MY", "Maldives": "MV", "Mali": "ML", "Malta": "MT", "Marshall Islands": "MH",
    "Mauritania": "MR", "Mauritius": "MU", "Mexico": "MX", "Micronesia": "FM", "Moldova": "MD",
    "Monaco": "MC", "Mongolia": "MN", "Montenegro": "ME", "Morocco": "MA", "Mozambique": "MZ",
    "Myanmar": "MM", "Namibia": "NA", "Nauru": "NR", "Nepal": "NP", "Netherlands": "NL",
    "New Zealand": "NZ", "Nicaragua": "NI", "Niger": "NE", "Nigeria": "NG", "North Korea": "KP",
    "North Macedonia": "MK", "Norway": "NO", "Oman": "OM", "Pakistan": "PK", "Palau": "PW",
    "Palestine": "PS", "Panama": "PA", "Papua New Guinea": "PG", "Paraguay": "PY", "Peru": "PE",
    "Philippines": "PH", "Poland": "PL", "Portugal": "PT", "Qatar": "QA", "Romania": "RO",
    "Russia": "RU", "Rwanda": "RW", "Saint Kitts and Nevis": "KN", "Saint Lucia": "LC",
    "Saint Vincent and the Grenadines": "VC", "Samoa": "WS", "San Marino": "SM",
    "Sao Tome and Principe": "ST", "Saudi Arabia": "SA", "Senegal": "SN", "Serbia": "RS",
    "Seychelles": "SC", "Sierra Leone": "SL", "Singapore": "SG", "Slovakia": "SK", "Slovenia": "SI",
    "Solomon Islands": "SB", "Somalia": "SO", "South Africa": "ZA", "South Korea": "KR",
    "South Sudan": "SS", "Spain": "ES", "Sri Lanka": "LK", "Sudan": "SD", "Suriname": "SR",
    "Sweden": "SE", "Switzerland": "CH", "Syria": "SY", "Taiwan": "TW", "Tajikistan": "TJ",
    "Tanzania": "TZ", "Thailand": "TH", "Timor-Leste": "TL", "Togo": "TG", "Tonga": "TO",
    "Trinidad and Tobago": "TT", "Tunisia": "TN", "Turkey": "TR", "Turkmenistan": "TM", "Tuvalu": "TV",
    "Uganda": "UG", "Ukraine": "UA", "United Arab Emirates": "AE", "United Kingdom": "GB",
    "United States": "US", "Uruguay": "UY", "Uzbekistan": "UZ", "Vanuatu": "VU", "Vatican City": "VA",
    "Venezuela": "VE", "Vietnam": "VN", "Yemen": "YE", "Zambia": "ZM", "Zimbabwe": "ZW"
  };

  // Función para convertir código ISO a emoji bandera
  function getFlagEmoji(countryCode) {
    const codePoints = countryCode
      .toUpperCase()
      .split('')
      .map(char => 127397 + char.charCodeAt(0));
    return String.fromCodePoint(...codePoints);
  }

  // ── Construir el select de prefijos telefónicos ──────────────────

  const selectPais = document.getElementById('regPais');
  const selectPrefijo = document.getElementById('regTelPrefijo');

  if (selectPais && selectPrefijo) {
    // Limpiar opciones existentes (por si acaso)
    selectPrefijo.innerHTML = '';

    // Añadir opción por defecto
    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = '';
    selectPrefijo.appendChild(defaultOption);

    // Construir opciones con bandera + prefijo (sin nombre del país)
    for (const opt of selectPais.querySelectorAll('option')) {
      if (!opt.value) continue; // saltar el "Selecciona un país"

      const prefijo = PREFIJOS_PAIS[opt.value];
      const codigoPais = PAIS_TO_FLAG[opt.value];
      if (!prefijo || !codigoPais) continue;

      const option = document.createElement('option');
      option.value = opt.value;
      // Mostrar: emoji bandera + prefijo (ej: 🇪🇸 +34)
      const flag = getFlagEmoji(codigoPais);
      option.textContent = `${flag} ${prefijo}`;
      selectPrefijo.appendChild(option);
    }

    // Sincronizar: prefijo → país
    selectPrefijo.addEventListener('change', () => {
      if (selectPrefijo.value) {
        selectPais.value = selectPrefijo.value;
        selectPais.dispatchEvent(new Event('change'));
      }
    });

    // Sincronizar: país → prefijo
    selectPais.addEventListener('change', () => {
      if (selectPais.value) {
        selectPrefijo.value = selectPais.value;
      }
    });
  }


});


///////////////////
// Main
///////////////////

// Cambio de pestaña en el login / registrarse ( IMPORTANTE NO QUITAR )
for (const btn of document.querySelectorAll('.ff-tab-btn')) {
  btn.addEventListener('click', () => ffShowTab(btn.dataset.ffTarget));
}

// Cambio de ojo para ver las contraseñas
document.querySelector("#eye-contraseña").addEventListener("click", () => {
  const input = document.querySelector("#loginPassword");
  const visible = input.type === "text";
  input.type = visible ? "password" : "text";
  document.querySelector("#eye-contraseña-login").classList.toggle("fa-eye");
  document.querySelector("#eye-contraseña-login").classList.toggle("fa-eye-slash");
});

document.querySelector("#eye-contraseña-registro").addEventListener("click", () => {
  const input = document.querySelector("#regPassword");
  const visible = input.type === "text";
  input.type = visible ? "password" : "text";
  document.querySelector("#eye-icon-registro").classList.toggle("fa-eye");
  document.querySelector("#eye-icon-registro").classList.toggle("fa-eye-slash");
});

document.querySelector("#eye-confirmar-contraseña").addEventListener("click", () => {
  const input = document.querySelector("#regPasswordConfirm");
  const visible = input.type === "text";
  input.type = visible ? "password" : "text";
  document.querySelector("#eye-contraseña-registrarse").classList.toggle("fa-eye");
  document.querySelector("#eye-contraseña-registrarse").classList.toggle("fa-eye-slash");
});



// Quitar el error cuando se escribre otra vez en el campo
const campos = document.querySelectorAll('.form-control, .form-select');

campos.forEach(function (campo) {
  campo.addEventListener('input', limpiarError);
  campo.addEventListener('change', limpiarError); // útil para <select> y fecha
});


// Valicadiones
validaciones();



