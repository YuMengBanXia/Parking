<?php
namespace es\ucm\fdi\aw\ePark;

class SAParking{
    
   

    public static function mostrarParkingsLibres(){ //mostrar parkings con plazas disponibles 
        $daoParking = ParkingDAO::getSingleton();
        return $daoParking->showAvailables();
    }

    public static function obtenerParkingPorId($id) {
        $daoParking = ParkingDAO::getSingleton();
        return $daoParking->getById($id);
    }

    public static function modificarParking($id, $dir, $ciudad, $CP, $precio, $n_plazas) {
        $daoParking = ParkingDAO::getSingleton();
        $parking = new TOParking($id, $dir, $ciudad, $CP, $precio, $n_plazas);
        return $daoParking->update($parking);
    }

    public static function modificarParkingObj($parking) {
        $daoParking = ParkingDAO::getSingleton();
        return $daoParking->update($parking);
    }

    public static function registrarParking($dir, $ciudad, $CP, $precio, $n_plazas) {
        $daoParking = ParkingDAO::getSingleton();
        $parking = new TOParking(null, $dir, $ciudad, $CP, $precio, $n_plazas);
        $daoParking->insert($parking);
    }
    public static function eliminarParking($id) {
        $daoParking = ParkingDAO::getSingleton();
        $daoParking->delete($id);
    }

}

?>