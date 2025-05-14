<?php
declare(strict_types=1);

namespace es\ucm\fdi\aw\ePark;

session_start();                        // ← Añadir sesión
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/includes/config.php';

// 1) Recoger parámetros
$desde = $_GET['fechaInicio'] ?? '';
$hasta = $_GET['fechaFin']    ?? '';

// 2) Validar formato de fecha
try {
    $d = new \DateTime($desde);
    $h = new \DateTime($hasta);
} catch (\Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Formato de fecha inválido. Usa YYYY-MM-DD.'
    ]);
    exit;
}

if ($d > $h) {
    http_response_code(400);
    echo json_encode([
        'status'  => 'error',
        'message' => '"Desde" debe ser anterior o igual a "Hasta".'
    ]);
    exit;
}

// 3) Lógica según rol
$tipo = $_SESSION['tipo'] ?? '';
$dni  = $_SESSION['dni']  ?? '';

try {
    if ($tipo === 'propietario' && $dni) {
        // Sólo pagos de parkings de este propietario
        $pagos = SAPago::listarPorRangoFechaYPropietario(
            $d->format('Y-m-d'),
            $h->format('Y-m-d'),
            $dni
        );
    } else {
        // Administrador (o cualquier otro rol): todos los pagos
        $pagos = SAPago::listarPorRangoFecha(
            $d->format('Y-m-d'),
            $h->format('Y-m-d')
        );
    }

    echo json_encode([
        'status' => 'success',
        'data'   => $pagos
    ]);
} catch (\Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Error interno: ' . $e->getMessage()
    ]);
}
