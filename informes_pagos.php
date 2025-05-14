<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';


$tituloPagina = 'Informe de Pagos';

$html = <<<EOF
  <form id="frmInforme">
    <label>Desde: <input type="date" id="from_date"></label>
    <label>Hasta: <input type="date" id="to_date"></label>
    <button type="submit" id="btnBuscar">Buscar</button>
    <img id="spinner" src="img/spinner.gif" alt="Cargando…" width="24" style="display:none">
  </form>

  <table id="tablaPagos">
    <thead>
      <tr>
        <th>ID</th>
        <th>DNI</th>
        <th>Importe (€)</th>
        <th>Fecha</th>
      </tr>
    </thead>
    <tbody id="cuerpoTabla">
      <tr><td colspan="4">Introduce un rango y pulsa “Buscar”.</td></tr>
    </tbody>
  </table>
EOF;

$contenidoPrincipal = <<<EOS
  <h3>Informe de Pagos</h3>
  $html
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="JS/informe_pago.js"></script>
EOS;
  

require_once __DIR__ .'/includes/vistas/plantilla/plantilla.php';
