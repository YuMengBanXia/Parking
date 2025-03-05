<?php
require_once __DIR__.'/ParkingDAO.php';
require_once __DIR__.'/TOParking.php';
require_once __DIR__.'/TicketDAO.php';
require_once __DIR__.'/TOTicket.php';
class SAParking{
    private static $daoParking; 
    private static $daoTicket;

    public static function inicializar(){
        self::$daoParking=ParkingDAO::getSingleton();
        self::$daoTicket=TicketDAO::getSingleton();
    }
    public static function obtenerParkingPorId($id) {
        return self::$daoParking->getById($id);
    }

    public static function mostrarParkingsLibres(){ //mostrar parkings con plazas disponibles 
        return self::$daoParking->showAvailables();
    }

    public static function modificarParking($id, $dir, $ciudad, $CP, $precio, $n_plazas) {
        $parking = new TOParking($id, $dir, $ciudad, $CP, $precio, $n_plazas);
        return self::$daoParking->update($parking);
    }

    public static function registrarParking($dir, $ciudad, $CP, $precio, $n_plazas) {
        $parking = new TOParking(null, $dir, $ciudad, $CP, $precio, $n_plazas);
        self::$daoParking->insert($parking);
    }
    public static function eliminarParking($id) {
        self::$daoParking->delete($id);
    }
   
    public static function nuevoTicket($id) {
        $parking = self::obtenerParkingPorId($id);
        if(empty($parking)){
            return 0;
        }
        if($parking->getNPlazas() > 0){
            $n=$parking->getNPlazas();
            $n--;
            $parking->setNPlazas($n);
            if(empty(self::$daoParking->update($parking))){
                return 0;
            }

            $codigo = self::$daoTicket->lastCodigo($id);
            $codigo = $codigo + 1;
            $ticket = new TOTicket($codigo,$id);
            if(empty(self::$daoTicket->insert($ticket))){
                return 0;
            }
            return $codigo;
        }
        else return 0;
    }

}

?>