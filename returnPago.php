<?php
require_once __DIR__.'/includes/config.php';
require_once 'redsys/apiRedsys.php';

$data    = $_POST;  // Redsys reenvía los mismos parámetros
$response= $data["Ds_Response"] ?? '';
$order   = $data["Ds_Order"]    ?? '';

if ($response === "0000") {
    echo "<h1>Pago exitoso para el ticket #{$order}</h1>";
} else {
    echo "<h1>Pago rechazado (código {$response})</h1>";
}
