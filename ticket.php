<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__.'/includes/clases/SAParking.php';




$contenidoPrincipal = <<<EOS
   <h3>Coger Ticket</h3>
EOS;

$tituloPagina='Coger Ticket';


require_once __DIR__ .'/includes/vistas/plantilla/plantillaTicket.php';

SAParking::inicializar();
if(!empty($_POST['parking_id']) && !empty($_POST['matricula'])){
    $ticket = SAParking::buscarMatricula($_POST['matricula']);
    if(!empty($ticket)){
        $id = $ticket->get_id();
        echo "<h4>La matrícula introducida ya está registrada en el parking $id</h4>";
        require_once __DIR__ .'/includes/vistas/comun/scriptParkingsLibres.php';
    }
    else{
        $ticket = SAParking::nuevoTicket($_POST['parking_id'],$_POST['matricula']);
        if(empty($ticket)){
            echo "<h4>Ha habido un error al sacar el ticket, por favor vuelva a seleccionar un parking</h4>";
            require_once __DIR__ .'/includes/vistas/comun/scriptParkingsLibres.php';
        }
        else{
            $codigo = $ticket->get_codigo();
            $fecha = $ticket->get_fecha()->format('d-m-Y H:i:s');
            echo "<h4>Ticket sacado con éxito! Su código es $codigo y se ha sacado el $fecha</h4>";   
        }
    }
    unset($_POST['parking_id'],$_POST['matricula']);
}
else{
    echo "<h4>Introduzca los datos por favor</h4>";
    require_once __DIR__ .'/includes/vistas/comun/scriptParkingsLibres.php';
}

?>