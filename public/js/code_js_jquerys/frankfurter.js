"user strict";

// Configuración de pares de divisas
const CURRENCY_PAIRS = [
  { base: 'EUR', quote: 'USD' },
  { base: 'GBP', quote: 'JPY' },
  { base: 'USD', quote: 'CHF' },
  { base: 'AUD', quote: 'USD' },
  { base: 'USD', quote: 'CAD' },
  { base: 'EUR', quote: 'GBP' },
  { base: 'NZD', quote: 'USD' }
];

// Variable para almacenar el estado
let tickerData = [];

// Función principal para actualizar el ticker
async function updateTicker() {
  const track = document.querySelector('.ticker-track');
  if (!track) {
    return;
  }

  try {
    // Obtener datos de la API
    const rates = await fetchAllRates();
    tickerData = rates;
    
    // Generar HTML con los datos actualizados
    const itemsHTML = generateTickerItems(rates);
    
    // Actualizar el contenido (manteniendo el efecto de duplicación)
    track.innerHTML = itemsHTML + itemsHTML;
      
  } catch (error) {
    // Mantener datos estáticos si falla la API
    track.innerHTML = getStaticHTML();
  }
}

// Obtener todas las tasas de cambio
async function fetchAllRates() {
  const results = [];
  const yesterday = getYesterdayDate();
  
  for (const pair of CURRENCY_PAIRS) {
    try {
      // Obtener tasa actual
      const currentRate = await fetchRate(pair.base, pair.quote);
      
      // Obtener tasa de ayer (para calcular cambio)
      let yesterdayRate = null;
      try {
        yesterdayRate = await fetchRate(pair.base, pair.quote, yesterday);
      } catch (e) {
        // Si no hay datos de ayer, usar la tasa actual
        yesterdayRate = currentRate;
      }
      
      // Calcular cambio porcentual
      const change = yesterdayRate > 0 
        ? ((currentRate - yesterdayRate) / yesterdayRate) * 100 
        : 0;
      
      // Determinar dirección
      const direction = change > 0.01 ? 'up' : change < -0.01 ? 'down' : 'neutral';
      
      if(direction === 'neutral') change = 0;

      results.push({
        ...pair,
        rate: currentRate,
        change: change,
        direction: direction
      });
        
    } catch (error) {
      // Usar datos de ejemplo si falla
      results.push({
        ...pair,
        rate: getFallbackRate(pair.base, pair.quote),
        change: 0,
        direction: 'neutral'
      });
    }
  }
  
  return results;
}

// Obtener una tasa específica
async function fetchRate(base, quote, date = null) {
  let url = `https://api.frankfurter.dev/v2/rate/${base}/${quote}`;
  if (date) {
    url += `?date=${date}`;
  }
  
  const response = await fetch(url);
  
  if (!response.ok) {
    throw new Error(`HTTP error! status: ${response.status}`);
  }
  
  const data = await response.json();
  return data.rate;
}

// Obtener fecha de ayer (día hábil)
function getYesterdayDate() {
  const today = new Date();
  let yesterday = new Date(today);
  yesterday.setDate(yesterday.getDate() - 1);
  
  // Si es domingo (0) o sábado (6), ir al viernes
  while (yesterday.getDay() === 0 || yesterday.getDay() === 6) {
    yesterday.setDate(yesterday.getDate() - 1);
  }
  
  return yesterday.toISOString().split('T')[0];
}

// Generar HTML para los items del ticker
function generateTickerItems(ratesData) {
  if (!ratesData || ratesData.length === 0) {
    return getStaticHTML();
  }
  
  return ratesData.map(item => {
    const sign = item.change > 0 ? '+' : '';
    const formattedChange = `${sign}${item.change.toFixed(2)}%`;
    const price = item.rate.toFixed(item.rate < 10 ? 4 : 2);
    
    return `
      <span class="ticker-item ${item.direction}">
        <span class="pair">${item.base}/${item.quote}</span>
        <span class="price mono">${price}</span>
        <span class="delta mono">${formattedChange}</span>
      </span>
    `;
  }).join('');
}

// Datos de fallback (estáticos)
function getStaticHTML() {
  return `
    <span class="ticker-item up"><span class="pair">EUR/USD</span><span class="price mono">1.0847</span><span class="delta mono">+0.12%</span></span>
    <span class="ticker-item down"><span class="pair">GBP/JPY</span><span class="price mono">198.32</span><span class="delta mono">-0.34%</span></span>
    <span class="ticker-item up"><span class="pair">USD/CHF</span><span class="price mono">0.8912</span><span class="delta mono">+0.08%</span></span>
    <span class="ticker-item up"><span class="pair">AUD/USD</span><span class="price mono">0.6621</span><span class="delta mono">+0.21%</span></span>
    <span class="ticker-item down"><span class="pair">USD/CAD</span><span class="price mono">1.3654</span><span class="delta mono">-0.15%</span></span>
    <span class="ticker-item up"><span class="pair">EUR/GBP</span><span class="price mono">0.8503</span><span class="delta mono">+0.05%</span></span>
    <span class="ticker-item down"><span class="pair">NZD/USD</span><span class="price mono">0.6034</span><span class="delta mono">-0.19%</span></span>
  `;
}

// Datos de fallback para tasas individuales
function getFallbackRate(base, quote) {
  const fallbackRates = {
    'EUR/USD': 1.0847,
    'GBP/JPY': 198.32,
    'USD/CHF': 0.8912,
    'AUD/USD': 0.6621,
    'USD/CAD': 1.3654,
    'EUR/GBP': 0.8503,
    'NZD/USD': 0.6034
  };
  return fallbackRates[`${base}/${quote}`] || 1.0;
}

// Iniciar el ticker
function initTicker() {    
  // Actualizar inmediatamente
  updateTicker();
  
  // Actualizar cada 60 segundos
  setInterval(updateTicker, 60000);
}

// Ejecutar cuando el DOM esté listo
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initTicker);
} else {
  // Si el DOM ya está cargado
  initTicker();
}

// Exportar funciones para uso global (opcional)
window.ticker = {
  update: updateTicker,
  refresh: updateTicker,
  data: () => tickerData
};