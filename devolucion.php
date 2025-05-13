<?php
require_once __DIR__ . '/includes/config.php';
/*require_once 'redsys/apiRedsys.php';

// 1) Recoger datos de POST
$orderEu    = $_SESSION['num_orden']  ?? '';
$amountEu   = $_SESSION['pago_cantidad'] ?? '';
$codigo = $_SESSION['pago_id'] ?? '';
unset($_SESSION['num_orden']);
unset($_SESSION['pago_cantidad']);
unset($_SESSION['pago_id']);
if (!$orderEu || !is_numeric($amountEu) || $amountEu <= 0 || !$codigo) {
    http_response_code(400);
    exit('Faltan o son inválidos los datos de devolución.');
}

// 2) Parámetros Redsys
$fuc      = '999008881';   // tu comercio de pruebas
$terminal = '1';
$key      = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7'; // clave HMAC en Base64

// 3) Convertir importe a céntimos y a 12 dígitos
$amountCents = str_pad((int)round($amountEu * 100), 12, '0', STR_PAD_LEFT);

// 4) Montar petición REST tipo 3 (devolución)
$api = new RedsysAPI();
$params = [
    'DS_MERCHANT_ORDER'           => $orderEu,
    'DS_MERCHANT_MERCHANTCODE'    => $fuc,
    'DS_MERCHANT_TERMINAL'        => $terminal,
    'DS_MERCHANT_TRANSACTIONTYPE' => '3',
    'DS_MERCHANT_CURRENCY'        => '978',
    'DS_MERCHANT_AMOUNT'          => $amountCents,
];
foreach ($params as $k => $v) {
    $api->setParameter($k, $v);
}

// 5) Codificar y firmar
$merchantParams = $api->createMerchantParameters();
$signature      = $api->createMerchantSignature($key);

// 6) Enviar con cURL al servicio de devoluciones
$endpoint = 'https://sis-t.redsys.es:25443/sis/rest/trataPeticionREST';
$body = json_encode([
    'Ds_SignatureVersion'          => 'HMAC_SHA256_V1',
    'Ds_MerchantParameters'        => $merchantParams,
    'Ds_Signature'                 => $signature,
]);
$ch = curl_init($endpoint);
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS     => $body,
    CURLOPT_RETURNTRANSFER => true,
]);

// … tras cerrar el cURL …
$response = curl_exec($ch);
$curlErr  = curl_error($ch);
curl_close($ch);

if ($curlErr) {
    $html = "<p class='error'>Error cURL: {$curlErr}</p>";
} else {
    // parseo del wrapper
    $wrapper = json_decode($response, true);

    if (! isset($wrapper['Ds_MerchantParameters']) ) {
        $html = "<p class='error'>Respuesta inválida de Redsys.</p>";
    } else {
        $apiResp = new RedsysAPI();
        // decodifico el Base64 interno
        $apiResp->decodeMerchantParameters($wrapper['Ds_MerchantParameters']);

        // leo los parámetros reales
        $dsResponse   = $apiResp->getParameter('Ds_Response') ?? '';
        $authCode     = $apiResp->getParameter('Ds_AuthorisationCode') ?? '';
        $errorMessage = $apiResp->getParameter('Ds_ErrorMessage') ?? '';

        if ($dsResponse === '0000') {
            \es\ucm\fdi\aw\ePark\SAReserva::cambiarEstado($codigo, 'cancelada');
            $html = "<p class='success'>Devolución OK. Código Redsys: {$authCode}</p>";
        } else {
            $texto = $errorMessage ?: "Código Redsys: {$dsResponse}";
            $html  = "<p class='error'>Error en devolución: {$texto}</p>";
        }
    }
}
*/

//Recoger datos
$orden    = $_SESSION['num_orden']  ?? '';
$importe   = $_SESSION['pago_cantidad'] ?? '';
$codigo = $_SESSION['pago_id'] ?? '';
unset($_SESSION['num_orden']);
unset($_SESSION['pago_cantidad']);
unset($_SESSION['pago_id']);
if (!$orden || !is_numeric($importe) || $importe <= 0 || !$codigo) {
    $http = <<<EOF
        <p>Ha habido un error tramitando su devolucion</p>
    EOF;
}
else{   
    \es\ucm\fdi\aw\ePark\SAReserva::cambiarEstado($codigo, 'cancelada');
    \es\ucm\fdi\aw\ePark\SAReserva::registrarPago($codigo);
    $html = <<<EOF
        <p>Se han devuelto {$importe} para la reserva con el codigo {$codigo} y numero de orden {$orden}</p>
    EOF;
}

$contenidoPrincipal = <<<EOS
   <h3>Resultado de la devolución</h3>
   $html
EOS;
$tituloPagina = 'Devolución';

require_once __DIR__ . '/includes/vistas/plantilla/plantilla.php';
