"user strict";

///////////////////
// Functions
///////////////////



///////////////////
// Main
///////////////////

const tablaSugerencias = document.querySelector("#tablaSugerencias");

// Jquery - DataTable
$(document).ready(function() {
  var tablaSugerencias;
  if ($('#tablaSugerencias').length > 0) {
    tablaSugerencias = $('#tablaSugerencias').DataTable({
      responsive: true,
      "pageLength": 5,
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