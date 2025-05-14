<?php
require_once __DIR__ . '/includes/config.php';


//$tituloPagina = 'Escoger parkings';

$form = new \es\ucm\fdi\aw\ePark\cogerTicket();
$htmlFormCoger = $form->Manage();



$contenidoPrincipal = <<<EOS
   <h3>Coger Ticket</h3>
   <h5> Importante: si usted entra y sale del parking en cuestión de segundos, aún así se le aplicará una tarifa fija de 0.01€.</h5>
    $htmlFormCoger 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="JS/tabla.js"></script>
   
EOS;



$tituloPagina = 'Coger Ticket';


require_once __DIR__ . '/includes/vistas/plantilla/plantilla.php';

?>

<script>
    initDataTable('#tablaParkings', {
        pageLength: 100,
        order: [
            [1, 'asc']
        ]
    });
</script>