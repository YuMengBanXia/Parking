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
$id = $_SESSION['pago_id']; 

if ($response === "0000") {
    $centimos = intval($miObj->getParameter("Ds_Amount"));
    $euros = $centimos / 100.0;
    $dni = \es\ucm\fdi\aw\ePark\SAParking::getDni($id);
    // Éxito: eliminamos o marcamos como pagado
    \es\ucm\fdi\aw\ePark\SATicket::eliminarTicket($id);
    \es\ucm\fdi\aw\ePark\SAPago::registrarPago($dni,$euros,new \DateTime());
    $html = <<<EOF
    <p>Pago exitoso para el ticket #{$id}</p>
    EOF;
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
