<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ .'/SAParking.php';


SAParking::inicializar();
//SAParking::registrarParking('Santesmases','Madrid',24700,1.0,100);
SAParking::eliminarParking(1);
SAParking::mostrarParkingsLibres();

?>