<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ .'/SAParking.php';


SAParking::inicializar();

$parking = SAParking::obtenerParkingPorId(1);
if($parking){
    SAPArking::eliminarParking(1, 'Calle Valvanera', 'Madrid', 12345, 1.0, 50);
    echo "Parking eliminado exitosamente";
}
else{
    echo "Parking con id 1 no existe";
}
SAParking::mostrarParkingsLibres();

?>