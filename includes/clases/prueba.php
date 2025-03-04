<?php

require_once __DIR__ .'/SAParking.php';

SAParking::inicializar();
SAParking::mostrarParkingsLibres();
SAParking::registrarParking('Calle Valvanera','Madrid',12345,10,100);

?>