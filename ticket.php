<?php
require_once __DIR__ . '/includes/config.php';


//$tituloPagina = 'Escoger parkings';

$form = new \es\ucm\fdi\aw\ePark\cogerTicket();
$htmlFormCoger = $form->Manage();



$contenidoPrincipal = <<<EOS
   <h3>Coger Ticket</h3>
   <h5> Importante: si usted entra y sale del parking en cuestión de segundos, aún así se le aplicará una tarifa fija de 0.01€.</h5>
   $htmlFormCoger 
EOS;



$tituloPagina='Coger Ticket';


require_once __DIR__ .'/includes/vistas/plantilla/plantilla.php';

?>