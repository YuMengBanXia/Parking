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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script
    src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js">
    </script>
    <script src="JS/tabla.js"></script>
EOS;



$tituloPagina='Nueva Reserva';


require_once __DIR__ .'/includes/vistas/plantilla/plantilla.php';

?>
<script>
    initDataTable('#tablaParkings');
</script>