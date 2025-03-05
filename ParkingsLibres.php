<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__.'/includes/clases/TOParking.php';
    
require_once __DIR__.'/includes/clases/SAParking.php';

$contenidoPrincipal = <<<EOS
   <h2>  Escoja uno de los parkings que tenemos con plazas disponibles para usted</h2>
EOS;

require_once __DIR__ .'/includes/vistas/plantilla/plantilla.php';

require_once __DIR__ .'/includes/vistas/scriptParkingsLibres.php';


$tituloPagina='Seleccion Parking';




?>