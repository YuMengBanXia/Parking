<?php

require_once __DIR__.'/includes/config.php';
require_once 'redsys/apiRedsys.php';


$miObj = new RedsysAPI;
$encoded = $_REQUEST["Ds_MerchantParameters"] ?? '';
$signature = $_REQUEST["Ds_Signature"] ?? '';
$kc = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7';

if (empty($encoded) || empty($signature)) {
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
    if(empty(\es\ucm\fdi\aw\ePark\SATicket::eliminarTicket($id))){
        http_response_code(400);
        exit("Error en la eliminacion");
    }
    \es\ucm\fdi\aw\ePark\SAPago::registrarPago($dni,$euros,new \DateTime());
    echo "OK";  // Redsys requiere este “OK”
} else {
    // Fracaso: no tocamos la BD
    echo "KO";
}
?>