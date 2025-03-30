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

    public static function modificarParking($id, $dni, $precio, $dir, $ciudad, $CP, $n_plazas, $img) {
        $daoParking = ParkingDAO::getSingleton();
        $parking = new TOParking($id, $dni, $precio, $dir, $ciudad, $CP,$n_plazas, $img);
        return $daoParking->update($parking);
    }

    public static function modificarParkingObj($parking) {
        $daoParking = ParkingDAO::getSingleton();
        return $daoParking->update($parking);
    }

    public static function registrarParking($dni, $dir, $precio, $ciudad, $CP, $n_plazas, $img) {
        $daoParking = ParkingDAO::getSingleton();
        $parking = new TOParking(null, $dni, $precio, $dir, $ciudad, $CP,  $n_plazas, $img);
        return $daoParking->insert($parking);
    }
    public static function eliminarParking($id) {
        $daoParking = ParkingDAO::getSingleton();
        return $daoParking->delete($id);
    }

}

?>