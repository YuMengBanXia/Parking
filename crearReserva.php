<?php
require_once __DIR__ . '/includes/config.php';


if(empty($_SESSION['login'])){
    $html = <<<EOF
    <p>Por favor inicia sesión para acceder a la funcionalidad</p>
    EOF;
}
else{
    $dni = $_SESSION['dni'];
    $form = new \es\ucm\fdi\aw\ePark\crearForm($dni);
    $html = $form->Manage();
}



$contenidoPrincipal = <<<EOS
    <h3>Crear Reserva</h3>
    $html
    <script src="JS/validacionFecha.js"></script>
EOS;



$tituloPagina='Nueva Reserva';


require_once __DIR__ .'/includes/vistas/plantilla/plantilla.php';

?>