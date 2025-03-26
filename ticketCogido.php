<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once __DIR__ . '/includes/config.php';

$tituloPagina = 'Ticket cogido';


$formTicketCogido=new \es\ucm\fdi\aw\ePark\cogidoTicket();


$htmlFormCogido=$formTicketCogido->Manage();

$contenidoPrincipal = <<<EOS
   <h3>DATOS DEL TICKET</h3>
   $htmlFormCogido

EOS;




require_once __DIR__ .'/includes/vistas/plantilla/plantilla.php';

?>