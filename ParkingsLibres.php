<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . "/includes/clases/mostrarParkingsForm.php";

$tituloPagina = 'Escoger parkings';

$form = new mostrarParkingsForm();

$htmlFormRegistro = $form->Manage();



$contenidoPrincipal = <<<EOS
   <h3>  Escoja uno de los parkings que tenemos con plazas disponibles para usted</h3>
   $htmlFormRegistro
EOS;



$tituloPagina='Seleccion Parking';


require_once __DIR__ .'/includes/vistas/plantilla/plantilla.php';

?>