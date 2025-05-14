<?php

require_once __DIR__ . '/includes/config.php';

if(empty($_SESSION["login"])){
    $html = <<<EOF
    <p>Por favor vuelve a iniciar sesión para acceder a la funcionalidad</p>
    EOF;
}
else{
    $dni = $_SESSION['dni'];
    $codigo = $_REQUEST['codigo'];
    
    if(!empty($codigo)){
        if(empty(\es\ucm\fdi\aw\ePark\SAReserva::comprobarDni($dni, $codigo))) {
            $html = <<<EOF
                <p>La reserva seleccionada no pertenece a este usuario</p>
            EOF;
        }
        else{
            $reserva = \es\ucm\fdi\aw\ePark\SAReserva::getReserva($codigo);
            $html = <<<EOF
                <p>Detalles reserva:</p>
            EOF;
            
            $form = new \es\ucm\fdi\aw\ePark\cancelarForm($reserva);
            $html .= $form->Manage();
        }
    }
    else{
        $html = <<<EOF
            <p>No se ha seleccionado ninguna reserva</p>
        EOF;
    }
}



$contenidoPrincipal = <<<EOS
   <h3>Cancelar Reserva</h3>
   $html
EOS;



$tituloPagina='Mis Reservas';


require_once __DIR__ .'/includes/vistas/plantilla/plantilla.php';

?>