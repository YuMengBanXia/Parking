<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/includes/config.php';

$form = new \es\ucm\fdi\aw\ePark\nuevoParking();
$htmlFormCoger = $form->Manage();


$contenidoPrincipal = <<<EOS
   <h3>Crear Parking</h3>
   $htmlFormCoger
   
EOS;



$tituloPagina='Crear Parking';


require_once __DIR__ .'/includes/vistas/plantilla/plantilla.php';

?>