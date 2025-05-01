<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Informe de Pagos</title>
  <style>
    #spinner { display: none; vertical-align: middle; }
    table { border-collapse: collapse; width: 100%; margin-top: 1em; }
    th, td { padding: .5em; border: 1px solid #ccc; text-align: left; }
  </style>
</head>
<body>
  <h1>Informe de Pagos por Periodo</h1>

  <label>
    Desde:
    <input type="date" id="fechaInicio" />
  </label>
  <label>
    Hasta:
    <input type="date" id="fechaFin" />
  </label>
  <button id="btnVerInforme">Ver informe</button>
  <img id="spinner" src="img/spinner.gif" alt="Cargando…" width="24" />

  <div id="resultadoInforme"></div>

  <script>

  document.getElementById('btnVerInforme').addEventListener('click', () => {

    const desde = document.getElementById('fechaInicio').value;
    const hasta = document.getElementById('fechaFin').value;
    const resultado = document.getElementById('resultadoInforme');
    const spinner = document.getElementById('spinner');

    // Validaciones
    if (!desde || !hasta) {
      resultado.innerHTML = '<p style="color:red;">Selecciona ambas fechas.</p>';
      return;
    }

    if (desde > hasta) {
      resultado.innerHTML = '<p style="color:red;">"Desde" debe ser anterior o igual a "Hasta".</p>';
      return;
    }

    // Mostrar indicador de carga
    spinner.style.display = 'inline';
    resultado.innerHTML = '';

    fetch(`ajax_informe_pago.php?fechaInicio=${desde}&fechaFin=${hasta}`)
      .then(res => res.json())
      .then(json => {

        spinner.style.display = 'none';
        if (json.status !== 'success') {
          resultado.innerHTML = `<p style="color:red;">${json.message}</p>`;
          return;
        }

        const pagos = json.data;

        if (pagos.length === 0) {
          resultado.innerHTML = '<p>No se hallaron pagos en ese periodo.</p>';
          return;
        }

        // Construir tabla de resultados
        let html = '<table><thead><tr>'
                 + '<th>ID</th><th>DNI</th><th>Importe (€)</th><th>Fecha</th>'
                 + '</tr></thead><tbody>';
        pagos.forEach(p => {
          html += `<tr>
                     <td>${p.id}</td>
                     <td>${p.dni}</td>
                     <td>${parseFloat(p.importe).toFixed(2)}</td>
                     <td>${p.fechaPago}</td>
                   </tr>`;
        });
        html += '</tbody></table>';

        resultado.innerHTML = html;
      })
      .catch(err => {
        spinner.style.display = 'none';
        console.error(err);
        resultado.innerHTML = '<p style="color:red;">Error al cargar los datos.</p>';
      });
  });
  
  </script>
</body>
</html>
