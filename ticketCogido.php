<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once __DIR__ . '/includes/config.php';

$tituloPagina = 'Ticket cogido';


$ticket = $_GET['ticket'] ?? null;
$matricula = $_GET['matricula'] ?? null;
$fecha = $_GET['fecha'] ?? null;

$html='';
if($ticket!=null && $matricula!=null && $fecha!=null){
$html =<<<EOF
   ID del ticket: $ticket <br>
   Matrícula: $matricula<br>
   Fecha entrada: $fecha <br>
      <a href="index.php">
         <button type="button">Ir al inicio</button>
   </a>
EOF;
}


$htmlFormCogido=$formTicketCogido->Manage();

$contenidoPrincipal = <<<EOS
   <h3>DATOS DEL TICKET</h3>
   $html

EOS;

require_once __DIR__ .'/includes/vistas/plantilla/plantilla.php';

?>