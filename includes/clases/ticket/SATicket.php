<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__.'/TicketDAO.php';
require_once __DIR__.'/TOTicket.php';

require_once __DIR__.'/../parking/SAParking.php';

class SATicket{

    public static function buscarMatricula($matricula){
        $daoTicket = TicketDAO::getSingleton();
        return $daoTicket->searchMatricula($matricula);
    }

    /*Leyenda de códigos:
    0:Faltan datos
    1:Errores asociados a los datos del parking
    2:El coche con esta matrícula ya se encuentra en algún parking de nuestra base de datos
    3:Error en la base de datos (insertar, actualizar o eliminar)
    4:Ticket creado exitosamente
    */

        
    public static function nuevoTicket($id,$matricula) {
        
        $daoTicket = TicketDAO::getSingleton();
        if(empty($id) || empty($matricula)){
            return [0,0]; //No se ha especificado id o matricula,  error inesperado (se hace comprobaciones antes de la funcion)
        }
        $aux= SAParking::obtenerParkingPorId($id);
        $parking=$aux[0];
        
        
        if(empty($parking)){
            return [1,0]; //Error, seleccionado un id de un parking que no existe
        }
        
        $ticket = self::buscarMatricula($matricula);
        if(!empty($ticket)){
            return [2,0]; //Ya hay un coche en el parking con esta matricula
        }
        if($parking->getNPlazas() > 0){
            $n=$parking->getNPlazas();
            $n--;
            $parking->setNPlazas($n);
            if(empty(SAParking::modificarParkingObj($parking))){
                return 3; //Ha ocurrido un error al actualizar los datos del parking en la base de datos
            }

            $codigo = $daoTicket->lastCodigo($id);
            $codigo = $codigo + 1;
            $ticket = new TOTicket($codigo,$id,$matricula);
            $fecha=$ticket->get_fecha();
            if(empty($daoTicket->insert($ticket))){
                return 3; //Error al insertar el ticket en la base de datos
            }
            
        }
        else return 1; //Error, el usuario solo puede seleccionar parkings libres

        $datos = [
            'codigo' => $codigo,
            'fecha' => $fecha
        ];
        return [4, $datos];
    }
}
?>