<?php

require_once __DIR__ . '/includes/config.php';

if(empty($_SESSION["login"])){
    $html = <<<EOF
    <p>Por favor vuelve a iniciar sesión para acceder a la funcionalidad</p>
    EOF;
}
else{
    $dni = $_SESSION['dni'];
    if(isset($_GET['codigo'])){
        $codigo = $_GET['codigo'];
    }
    elseif(isset($_POST['codigo'])){
        $codigo = $_POST['codigo'];
    }
    else{
        $html = <<<EOF
            <p>No se ha seleccionado ninguna reserva</p>
        EOF;
    }
    
    if(!empty($codigo)){
        if(empty(\es\ucm\fdi\aw\ePark\SAReserva::comprobarDni($dni, $codigo))) {
            $html = <<<EOF
                <p>La reserva seleccionada no pertenece a este usuario</p>
            EOF;
        }
        else{
            /*$parking = \es\ucm\fdi\aw\ePark\SAParking::obtenerParkingPorId($id);
            $form = new \es\ucm\fdi\aw\ePark\modificarForm($parking);
            $html = $form->Manage();*/
        }
    }
}



$contenidoPrincipal = <<<EOS
   <h3>Cancelar Reserva</h3>
   $html
EOS;



$tituloPagina='Mis Reservas';


require_once __DIR__ .'/includes/vistas/plantilla/plantilla.php';

?>