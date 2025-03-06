<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__.'/includes/clases/SAParking.php';


$contenidoPrincipal = <<<EOS
   <h3>Coger Ticket</h3>
EOS;

$tituloPagina='Coger Ticket';


require_once __DIR__ .'/includes/vistas/plantilla/plantillaTicket.php';

require_once __DIR__ .'/includes/vistas/comun/procesarTicket.php';
?>