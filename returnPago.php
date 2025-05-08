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
            $ticket = \es\ucm\fdi\aw\ePark\SATicket::buscarCodigo($codigo);
            $id = $ticket->get_id();
            $dni = \es\ucm\fdi\aw\ePark\SAParking::getDni($id);
            // Éxito: eliminamos ticket y registramos el pago
            \es\ucm\fdi\aw\ePark\SATicket::eliminarTicket($codigo);
            \es\ucm\fdi\aw\ePark\SAPago::registrarPago($dni,$euros,new \DateTime());
            $html = <<<EOF
                <p>Pago exitoso para el ticket #{$codigo}</p>
            EOF;
            break;
        case 'reserva':
            switch($miObj->getParameter('Ds_TransactionType')){
                case '0':
                    //En caso de pagar una reserva, como el usuario puede llegar a cancelar la reserva no se puede todavía registrar el pago
                    \es\ucm\fdi\aw\ePark\SAReserva::cambiarEstado($codigo,'pagada');
                    $html = <<<EOF
                        <p>Reserva pagada exitosamente</p>
                    EOF;
                    break;
                case '2':
                    //En este caso de devuelve una reserva, por lo que se procede a cancelarla
                    \es\ucm\fdi\aw\ePark\SAReserva::cambiarEstado($codigo,'cancelada');
                    $html = <<<EOF
                        <p>Devolución tramitada correctamente. Reserva cancelada</p>
                    EOF;
                    break;
                default:
                $html = <<<EOF
                    <p>Error en el tratamiento del tipo de transacción</p>
                EOF;
                break;
            }
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
