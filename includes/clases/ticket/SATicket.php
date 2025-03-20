<?php
require_once __DIR__.'/TicketDAO.php';
require_once __DIR__.'/TOTicket.php';
require_once __DIR__.'../SAParking.php';

class SATicket{

    public static function buscarMatricula($matricula){
        $daoTicket = TicketDAO::getSingleton();
        return $daoTicket->searchMatricula($matricula);
    }

    /*Leyenda de códigos:
    0:Error inesperado
    1:Faltan datos
    2:El coche con esta matrícula ya se encuentra en algún parking de nuestra base de datos
    3:Error en la base de datos (insertar, actualizar o eliminar)
    4:Ticket creado exitosamente
    */
    public static function nuevoTicket($id,$matricula) {
        $daoTicket = TicketDAO::getSingleton();
        if(empty($id) || empty($matricula)){
            return 1; //No se ha especificado id o matricula
        }
        $parking = SAParking::obtenerParkingPorId($id);
        if(empty($parking)){
            return 0; //Error inesperado, el usuario no debería poder seleccionar un id de un parking que no existe
        }
        $ticket = self::buscarMatricula($matricula);
        if(!empty($ticket)){
            return 2; //Ya hay un coche en el parking con esta matricula
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
            if(empty($daoTicket->insert($ticket))){
                return 3; //Error al insertar el ticket en la base de datos
            }
            return $ticket;
        }
        else return 0; //Error inesperado (se supone que el usuario solo puede seleccionar parkings libres)

        $datos = [
            'codigo'    => $codigo,
            'fecha'     => date('Y-m-d H:i:s')  // Ejemplo de fecha/hora de creación
        ];
        return [4, $datos];
    }
}
?>