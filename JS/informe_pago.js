// js/informes_pagos.js
$(function() {
    const $frm     = $('#frmInforme');
    const $btn     = $('#btnBuscar');
    const $cuerpo  = $('#cuerpoTabla');
    const $spinner = $('#spinner');
    const isoRe    = /^\d{4}-\d{2}-\d{2}$/;
  
    $frm.on('submit', function(e) {
      e.preventDefault();
  
      // 1) Leer y normalizar "/" → "-"
      let desde = $('#from_date').val().trim().replace(/\//g,'-');
      let hasta = $('#to_date')  .val().trim().replace(/\//g,'-');
  
      // 2) Validar formato
      if (!isoRe.test(desde) || !isoRe.test(hasta)) {
        $cuerpo.html(`<tr><td colspan="4" class="error">
          Formato inválido. Usa YYYY-MM-DD.
        </td></tr>`);
        return;
      }
      if (desde > hasta) {
        $cuerpo.html(`<tr><td colspan="4" class="error">
          “Desde” debe ser anterior o igual a “Hasta”.
        </td></tr>`);
        return;
      }
  
      // 3) Mostrar spinner y limpiar tabla
      $spinner.show();
      $cuerpo.empty();
  
      // 4) Llamada AJAX
      $.getJSON('ajax_informe_pago.php', {
        fechaInicio: desde,
        fechaFin:    hasta
      })
      .done(function(json) {
        $spinner.hide();
        if (json.status !== 'success') {
          return $cuerpo.html(`<tr><td colspan="4" class="error">
            ${json.message}
          </td></tr>`);
        }
        const pagos = json.data;
        if (!pagos.length) {
          return $cuerpo.html('<tr><td colspan="4">No se encontraron pagos.</td></tr>');
        }
        // 5) Rellenar la tabla
        let html = '';
        $.each(pagos, function(_, p) {
          html += `<tr>
            <td>${p.id}</td>
            <td>${p.dni}</td>
            <td>${parseFloat(p.importe).toFixed(2)}</td>
            <td>${p.fechaPago}</td>
          </tr>`;
        });
        $cuerpo.html(html);
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
        $spinner.hide();
        console.error(textStatus, errorThrown);
        $cuerpo.html(`<tr><td colspan="4" class="error">
          Error al cargar datos: ${errorThrown}
        </td></tr>`);
      });
    });
  });
  