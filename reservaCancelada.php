<?php

require_once __DIR__ . '/includes/config.php';

$tituloPagina = 'Mis Reservas';


$codigo = $_GET['codigo'] ?? null;
$id = $_GET['id'] ?? null;
$matricula = $_GET['matricula'] ?? null;
$fecha_ini = $_GET['fecha_ini'] ?? null;
$importe = $_GET['importe'] ?? null;

$html='';
if($codigo != null && $matricula != null && $fecha_ini != null && $id != null && $importe != null){
    $html = <<<EOF
        Se ha completado la cancelacion de la reserva: <br>
            Codigo: $codigo <br>
            Parking: $id <br>
            Matrícula: $matricula<br>
            Fecha inicio: $fecha_ini <br>
            Importe devuelto: $importe <br>
    EOF;
}

$html .= <<<EOF
    <a href="misReservas.php">
        <button type="button">Volver a mis reservas</button>
    </a>
EOF;
     

$contenidoPrincipal = <<<EOS
   <h3>DATOS DE LA RESERVA</h3>
   $html
EOS;

require_once __DIR__ .'/includes/vistas/plantilla/plantilla.php';

?>