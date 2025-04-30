<?php
require_once __DIR__.'/includes/config.php';
require_once 'redsys/apiRedsys.php';

$miObj    = new RedsysAPI;
$encoded  = $_POST["Ds_MerchantParameters"] ?? '';
$signature= $_POST["Ds_Signature"]           ?? '';
$kc       = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7';

$params   = $miObj->decodeMerchantParameters($encoded);
$expectedSignature = $miObj->createMerchantSignatureNotif($kc, $encoded);
if ($signature !== $expectedSignature) {
    http_response_code(400);
    exit("Firma inválida");
}

$response = $params["Ds_Response"];   
$order    = intval($params["Ds_Order"]); 

if ($response === "0000") {
    // Éxito: eliminamos o marcamos como pagado
    \es\ucm\fdi\aw\ePark\SATicket::eliminarTicket($order);
    echo "OK";  // Redsys requiere este “OK”
} else {
    // Fracaso: no tocamos la BD
    echo "KO";
}
