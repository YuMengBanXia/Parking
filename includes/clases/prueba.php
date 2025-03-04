<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ .'/SAParking.php';

SAParking::inicializar();
SAParking::registrarParking('Calle Valvanera','Madrid',12345,1,100);
SAParking::mostrarParkingsLibres();

?>