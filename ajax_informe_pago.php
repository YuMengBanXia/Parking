<?php

namespace es\ucm\fdi\aw\ePark;

require_once __DIR__ . '/includes/config.php'

// Devolvemos siempre JSON
header('Content-type: application/json; charset=utf-8');

//Recoger fechas por GET
$desde = $_GET['fechaInicio'] ?? '';
$hasta = $_GET['fechaFin'] ?? '';


if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $desde)
 || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $hasta)) {
    http_response_code(400);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Formato de fecha inválido. Debe ser YYYY-MM-DD.'
    ]);
    exit;
}

try{

    $data = SAPago::listarPorRangoFechas($desde, $hasta);

    echo json_encode([
        'status' => 'success',
        'data'   => $data
    ]);
}catch (\Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Error interno: ' . $e->getMessage()
    ]);
}



?>