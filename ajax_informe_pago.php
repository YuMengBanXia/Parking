<?php
declare(strict_types=1);
namespace es\ucm\fdi\aw\ePark;

ini_set('display_errors','1'); ini_set('display_startup_errors','1'); error_reporting(E_ALL);
header("Content-Type: application/json; charset=utf-8");

require_once __DIR__.'/includes/config.php';

// Normalizar barras a guiones
$desde = str_replace('/', '-', $_GET['fechaInicio'] ?? '');
$hasta = str_replace('/', '-', $_GET['fechaFin']    ?? '');

// Validar formato YYYY-MM-DD
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $desde)
 || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $hasta)) {
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'Formato inválido: YYYY-MM-DD']);
    exit;
}

try {
  $pagos = \es\ucm\fdi\aw\ePark\SAPago::listarPorRangoFecha($desde, $hasta);
  // Mapear a array plano
  $data = array_map(fn($p)=>[
    'id'=>$p->getId(),
    'dni'=>$p->getDni(),
    'importe'=>number_format($p->getImporte(),2,'.',''),
    'fechaPago'=>$p->getFechaPago()
  ], $pagos);
  echo json_encode(['status'=>'success','data'=>$data]);
} catch(\Throwable $e) {
  http_response_code(500);
  echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
}
