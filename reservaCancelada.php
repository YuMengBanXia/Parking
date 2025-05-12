<?php

require_once __DIR__ . '/includes/config.php';

$tituloPagina = 'Mis Reservas';


$codigo = $_GET['codigo'] ?? null;
$id = $_GET['id'] ?? null;
$matricula = $_GET['matricula'] ?? null;
$fecha_ini = $_GET['fecha_ini'] ?? null;

$html='';
if($codigo != null && $matricula != null && $fecha_ini != null && $id != null){
    $html =<<<EOF
        Se ha completado la cancelacion de la reserva:
            Codigo: $codigo <br>
            Parking: $id
            Matrícula: $matricula<br>
            Fecha inicio: $fecha_ini <br>
        <a href="misReservas.php">
            <button type="button">Volver a mis reservas</button>
        </a>
    EOF;
}
     

$contenidoPrincipal = <<<EOS
   <h3>DATOS DE LA RESERVA</h3>
   $html

EOS;

require_once __DIR__ .'/includes/vistas/plantilla/plantilla.php';

?>