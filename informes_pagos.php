<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: sans-serif; padding:1em; }
    label { margin-right:1em; }
    input { width:120px; padding:0.25em; }
    #spinner { display:none; vertical-align:middle; margin-left:0.5em; }
    table { width:100%; border-collapse: collapse; margin-top:1em; }
    th, td { border:1px solid #ccc; padding:0.5em; }
    thead { background:#333; color:#fff; }
    .error { color:red; }
  </style>
</head>
<body>
  <h1>Informe de Pagos por Periodo</h1>

  <form id="frmInforme">
    <label>Desde: <input type="date" id="from_date" placeholder="YYYY-MM-DD"></label>
    <label>Hasta: <input type="date" id="to_date"   placeholder="YYYY-MM-DD"></label>
    <button id="btnBuscar" class="btn btn-primary">Buscar</button>
    <img id="spinner" src="img/spinner.gif" alt="Cargando…" width="24">
  </form>

  <table id="tablaPagos">
    <thead>
      <tr><th>ID</th><th>DNI</th><th>Importe (€)</th><th>Fecha</th></tr>
    </thead>
    <tbody id="cuerpoTabla">
      <tr><td colspan="4">Introduce un rango y pulsa “Buscar”.</td></tr>
    </tbody>
  </table>

  <!-- Lógica AJAX con jQuery -->
  <script src="js/informe_pago.js"></script>
</body>
</html>
