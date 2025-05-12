<?php
require_once __DIR__ . '/includes/config.php';


if(empty($_SESSION['login'])){
    $html = <<<EOF
    <p>Por favor inicia sesión para acceder a la funcionalidad</p>
    EOF;
}
else{
    $dni = $_SESSION['dni'];
    $form = new \es\ucm\fdi\aw\ePark\administrarReservas($dni);
    $html = $form->Manage();
}



$contenidoPrincipal = <<<EOS
   <h3>Gestionar reservas</h3>
   $html
   
EOS;



$tituloPagina='Mis Reservas';


require_once __DIR__ .'/includes/vistas/plantilla/plantilla.php';

?>