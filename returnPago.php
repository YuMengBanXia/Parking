<?php
require_once __DIR__.'/includes/config.php';
require_once 'redsys/apiRedsys.php';

$miObj = new RedsysAPI;
$encoded = $_REQUEST["Ds_MerchantParameters"] ?? '';
$signature = $_REQUEST["Ds_Signature"] ?? '';
$kc = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7';

if (!$encoded || !$signature) {
    http_response_code(400);
    exit('Faltan parámetros Redsys');
}

$params = $miObj->decodeMerchantParameters($encoded);
$expectedSignature = $miObj->createMerchantSignatureNotif($kc, $encoded);
if ($signature !== $expectedSignature) {
    http_response_code(400);
    exit("Firma inválida");
}

$response = $miObj->getParameter("Ds_Response");
$codigo = $_SESSION['pago_id']; 
$tipo = $_SESSION['pago_tipo'];
unset($_SESSION['pago_tipo']);
unset($_SESSION['pago_id']);

if ($response === "0000") {
    switch($tipo){
        case 'ticket':
            $centimos = intval($miObj->getParameter("Ds_Amount"));
            $euros = $centimos / 100.0;
            // …
            $ticket = \es\ucm\fdi\aw\ePark\SATicket::buscarCodigo($codigo);
            // Este método debería devolverte el id de parking asociado al ticket
            $idParking = $ticket->get_id(); 
            // O si tu método se llama distinto, usa el getter correcto:
            //    $idParking = $ticket->get_idParking();

            $dniPropietario = \es\ucm\fdi\aw\ePark\SAParking::getDni($idParking);

            // Elimina el ticket
            \es\ucm\fdi\aw\ePark\SATicket::eliminarTicket($codigo);

            $fecha = new \DateTime();

            // Ahora pasamos el nuevo parámetro $idParking antes de $importe
            \es\ucm\fdi\aw\ePark\SAPago::registrarPago(
                $dniPropietario,
                $idParking,
                $euros,
                $fecha->format('Y-m-d H:i:s')
            );
            $html = <<<EOF
                <p>Pago exitoso para el ticket #{$codigo}</p>
            EOF;
            break;
        case 'reserva':
            $num_orden = $miObj->getParameter('Ds_Order');
            \es\ucm\fdi\aw\ePark\SAReserva::setNumOrden($codigo,$num_orden);
            //En caso de pagar una reserva, como el usuario puede llegar a cancelar la reserva no se puede todavía registrar el pago
            \es\ucm\fdi\aw\ePark\SAReserva::cambiarEstado($codigo,'pagada');
            $html = <<<EOF
                <p>Reserva pagada exitosamente</p>
            EOF;
            break;
        default:
            $html = <<<EOF
                <p>Ha ocurrido un error en el sistema</p>
            EOF;
            break;
    }
} else {
    $html = <<<EOF
    <p>Pago rechazado {$response}</p>
    EOF;
}

$contenidoPrincipal = <<<EOS
   <h3>Resumen pago</h3>
   $html
EOS;



$tituloPagina='Pago';

require_once __DIR__ .'/includes/vistas/plantilla/plantilla.php';
