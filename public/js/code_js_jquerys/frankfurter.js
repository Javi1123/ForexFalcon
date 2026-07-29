"user strict";

///////////////////
// Functions
///////////////////

const convertCurrency = async (base, quote, amount) => {
  const url = `https://api.frankfurter.dev/v2/rate/${base}/${quote}`;
  
  return fetch(url)
    .then(response => {
      if (!response.ok) {
        throw new Error(`Error HTTP: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      // data.rate contiene el tipo de cambio
      const result = (amount * data.rate).toFixed(2);
      console.log(`${amount} ${base} = ${result} ${quote}`);
      return result;
    })
    .catch(error => {
      console.error('Error al obtener el tipo de cambio:', error);
    });
}

const cogerValor = () => {
  const tasasMoneda = document.querySelector("#tasasMoneda");
  console.log(tasasMoneda.children[0].children);
}

///////////////////
// Main
///////////////////

cogerValor();

convertCurrency("EUR","USD",1);
