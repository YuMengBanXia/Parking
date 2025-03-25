<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


include __DIR__ . "/includes/clases/cogidoTicket.php";

$tituloPagina = 'Ticket cogido';


$formTicketCogido=new cogidoTicket();


$htmlFormCogido=$formTicketCogido->Manage();

$contenidoPrincipal = <<<EOS
   <h3>Coger Ticket</h3>
 
   $htmlFormCogido
EOS;




require_once __DIR__ .'/includes/vistas/plantilla/plantilla.php';

?>