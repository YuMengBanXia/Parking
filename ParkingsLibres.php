<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__.'/includes/clases/TOParking.php';
    
require_once __DIR__.'/includes/clases/SAParking.php';



$contenidoPrincipal = <<<EOS
   <h3>  Escoja uno de los parkings que tenemos con plazas disponibles para usted</h2>
EOS;



$tituloPagina='Seleccion Parking';


require_once __DIR__ .'/includes/vistas/plantilla/plantillaTicket.php';

require_once __DIR__ .'/includes/vistas/comun/scriptParkingsLibres.php';




?>