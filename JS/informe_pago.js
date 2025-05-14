$(function() {
  const $frm     = $('#frmInforme');
  const $btn     = $('#btnBuscar');
  const $cuerpo  = $('#cuerpoTabla');
  const $spinner = $('#spinner');

  $frm.on('submit', function(e) {
    e.preventDefault();

    // Leer fechas de los inputs
    const desde = $('#from_date').val();
    const hasta = $('#to_date').val();

    if (!desde || !hasta) {
      return renderError('Debes seleccionar ambas fechas.');
    }
    if (desde > hasta) {
      return renderError('"Desde" debe ser anterior o igual a "Hasta".');
    }

    // Mostrar spinner y vaciar tabla
    $spinner.show();
    $cuerpo.empty();

    // Llamada AJAX
    $.ajax({
      url: 'ajax_informe_pago.php',
      method: 'GET',
      data: {
        fechaInicio: desde,
        fechaFin:    hasta
      },
      dataType: 'json'
    })
    .done(function(json) {
      $spinner.hide();
      if (json.status !== 'success') {
        return renderError(json.message);
      }
      if (!json.data.length) {
        return $cuerpo.html('<tr><td colspan="4">No se encontraron pagos.</td></tr>');
      }
      // Dibujar filas
      const html = json.data.map(p =>
        `<tr>
          <td>${p.id}</td>
          <td>${p.dni}</td>
          <td>${parseFloat(p.importe).toFixed(2)}</td>
          <td>${p.fechaPago}</td>
        </tr>`
      ).join('');
      $cuerpo.html(html);
    })
    .fail(function(_, textStatus, errorThrown) {
      $spinner.hide();
      console.error(textStatus, errorThrown);
      renderError('Error al cargar datos: ' + errorThrown);
    });
  });

  function renderError(msg) {
    $cuerpo.html(
      `<tr><td colspan="4" class="error">${msg}</td></tr>`
    );
  }
});
