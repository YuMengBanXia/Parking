<?php
require_once __DIR__.'.php';
class SAParking{
    private static $daoParking; 

    public static function __constructor(){
        $daoParking=ParkingDAO::getSingleton();
    }

    public static function mostrarParkingsLibres(){ //mostrar parkings con plazas disponibles 
        return daoParking->showAvailables();
    }




}



?>