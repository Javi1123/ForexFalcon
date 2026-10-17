/* ═══════════════════════════════════════════════════════
   PAÍSES — Carga de datos desde XML, reutilizable
   Cualquier JS del sitio puede hacer:
     const { cargarPaises, getFlagEmoji } = await import('./paises.js');
   ═══════════════════════════════════════════════════════ */

const RUTA_PAISES_XML = '/forexfalcon/public/data/paises.xml'; // ajusta si tu ruta real es otra

/**
 * Descarga y parsea paises.xml.
 * Devuelve un objeto: { "Spain": { prefijo: "+34", iso: "ES" }, ... }
 */
export async function cargarPaises() {
  const respuesta = await fetch(RUTA_PAISES_XML);
  const textoXml = await respuesta.text();
  const xml = new DOMParser().parseFromString(textoXml, 'application/xml');

  const paises = {};
  xml.querySelectorAll('pais').forEach((nodo) => {
    const nombre = nodo.getAttribute('nombre');
    paises[nombre] = {
      prefijo: nodo.getAttribute('prefijo'),
      iso: nodo.getAttribute('iso')
    };
  });

  return paises;
}

/**
 * Convierte un código ISO de país (ej. "ES") en su emoji de bandera (🇪🇸).
 */
export function getFlagEmoji(countryCode) {
  const codePoints = countryCode
    .toUpperCase()
    .split('')
    .map((char) => 127397 + char.charCodeAt(0));
  return String.fromCodePoint(...codePoints);
}