<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/includes/config.php';


//$tituloPagina = 'Escoger parkings';

$form = new \es\ucm\fdi\aw\ePark\cogerTicket();
$htmlFormCoger = $form->Manage();


$contenidoPrincipal = <<<EOS
   <h3>Coger Ticket</h3>
   $htmlFormCoger
   
  
EOS;



$tituloPagina='Coger Ticket';


require_once __DIR__ .'/includes/vistas/plantilla/plantilla.php';

?>