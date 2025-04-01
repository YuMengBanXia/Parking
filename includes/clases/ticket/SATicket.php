<?php

namespace es\ucm\fdi\aw\ePark;

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
            return 0; //No se ha especificado id o matricula,  error inesperado (se hace comprobaciones antes de la funcion)
        }
        $parking = SAParking::obtenerParkingPorId($id);        
        
        if(empty($parking)){
            return 1; //Error, seleccionado un id de un parking que no existe
        }
        
        $ticket = self::buscarMatricula($matricula);
        if(!empty($ticket)){
            return 2; //Ya hay un coche en el parking con esta matricula
        }
        $plazas = $parking->getNPlazas();
        $libre = self::libre($id,$plazas);
        if($libre){
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

    public static function libre($id, $plazas){
        $daoTicket = TicketDAO::getSingleton();
        $num = $daoTicket->ocupacion($id);
        return $num < $plazas;
    }

    public static function plazasOcupadas($id){
        $daoTicket = TicketDAO::getSingleton();
        return $daoTicket->ocupacion($id);
    }
}
?>