<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ .'/SAParking.php';


SAParking::inicializar();

$parking  = SAParking::obtenerIdParking(1);
if($parking){
    SAPArking::modificarParking(1, 'Calle Valvanera', 'Madrid', 12345, 1.0, 99);
    echo "Parking modificado exitosamente";
}
else{
    echo "Parking con id 1 no existe";
}
SAParking::mostrarParkingsLibres();

?>