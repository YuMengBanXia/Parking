<?php
require_once __DIR__ . '/includes/config.php';

$form = new \es\ucm\fdi\aw\ePark\pagarForm();
$html = $form->Manage();


$contenidoPrincipal = <<<EOS
   <h3>Pago</h3>
   $html
EOS;



$tituloPagina='Pago';


require_once __DIR__ .'/includes/vistas/plantilla/plantilla.php';

?>