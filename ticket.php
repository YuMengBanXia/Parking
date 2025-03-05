<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__.'/includes/clases/SAParking.php';




$contenidoPrincipal = <<<EOS
   <h3>  Escoja uno de los parkings que tenemos con plazas disponibles para usted</h2>
EOS;



$tituloPagina='Seleccion Parking';


require_once __DIR__ .'/includes/vistas/plantilla/plantilla.php';

SAParking::inicializar();
if(!empty($_POST['parking_id'])){
    $codigo = SAParking::nuevoTicket($_POST['parking_id']);
    if(empty($codigo)){
        echo "<h4>Ha habido un error al sacar el ticket, por favor vuelva a seleccionar un parking</h4>";
    }
    else echo "<h4>Ticket sacado con éxito! Su código es $codigo</h4>";
}

require_once __DIR__ .'/includes/vistas/comun/scriptParkingsLibres.php';

?>