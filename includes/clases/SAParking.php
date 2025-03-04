<?php
require_once __DIR__.'/ParkingDAO.php';
require_once __DIR__.'/TOParking.php';
class SAParking{
    private static $daoParking; 

    public static function inicializar(){
        self::$daoParking=ParkingDAO::getSingleton();
    }

    public static function obtenerParkingPorId($id) {
        return self::$daoParking->getById($id);
    }
    
    public static function mostrarParkingsLibres(){ //mostrar parkings con plazas disponibles 
        return self::$daoParking->showAvailables();
    }

    public static function registrarParking($dir, $ciudad, $CP, $precio, $n_plazas) {
        $parking = new TOParking(null, $dir, $ciudad, $CP, $precio, $n_plazas);
        self::$daoParking->insert($parking);
    }

    public static function modificarParking($id, $dir, $ciudad, $CP, $precio, $n_plazas) {
        $parking = new TOParking($id, $dir, $ciudad, $CP, $precio, $n_plazas);
        self::$daoParking->update($parking);
    }





}





?>