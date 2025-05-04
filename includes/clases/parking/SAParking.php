<?php
namespace es\ucm\fdi\aw\ePark;

class SAParking{
    
   
    /*Obsoleto
    public static function mostrarParkingsLibres(){ //mostrar parkings con plazas disponibles 
        $daoParking = ParkingDAO::getSingleton();
        return $daoParking->showAvailables();
    }
    */
    public static function mostrarParkings() {
        $daoParking = ParkingDAO::getSingleton();
        return $daoParking->getAll();
    }

    public static function obtenerParkingPorId($id) {
        $daoParking = ParkingDAO::getSingleton();
        return $daoParking->getById($id);
    }

    public static function modificarParking($id,$precio, $dir, $ciudad, $CP, $n_plazas, $img) {
        $daoParking = ParkingDAO::getSingleton();
        $parking = new TOParking($id, null, $precio, $dir, $ciudad, $CP,$n_plazas, $img);
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

    public static function getByDni($dni) {
        $daoParking = ParkingDAO::getSingleton();
        return $daoParking->getByDni($dni);
    }

    public static function comprobarDni($id,$dni){
        $daoParking = ParkingDAO::getSingleton();
        return $daoParking->getDni($id) === $dni;
    }

    public static function getDni($id){
        $daoParking = ParkingDAO::getSingleton();
        return $daoParking->getDni($id);
    }
}

?>