/**
 * Inicializa una tabla con DataTables.
 *
 * @param {string} tableSelector - Selector de jQuery de la tabla (p.ej. '#tablaReservas').
 * @param {object} [options={}] - Opciones adicionales de DataTables.
 */
function initDataTable(tableSelector, options = {}) {
    // Asegurarse de que jQuery y DataTables están cargados
    if (typeof $ === 'undefined' || !$.fn.DataTable) {
        console.error('jQuery o DataTables no están disponibles.');
        return;
    }
    // Valores por defecto
    const defaults = {
        language: {
        url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
        },
        pageLength: 10,        // filas por página
        order: [[0, 'asc']],   // orden inicial: columna 0 ascendente
        responsive: true       // adaptativo a móviles
    };
    // Fusionar opciones por defecto con las pasadas
    const cfg = Object.assign({}, defaults, options);
    
    // Inicializar DataTable
    $(document).ready(() => {
        const $tbl = $(tableSelector);
        if (!$tbl.length) return;

        const dataTable = $tbl.DataTable(cfg);
        
        // Forzar actualización inicial
        dataTable.on('draw', () => {
            $(document).trigger('draw.dt');
        });
    });
    }
  