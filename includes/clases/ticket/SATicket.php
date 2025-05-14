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

        $nPlazas = $parking->getNPlazas();
        $ocupadas = htmlspecialchars(SAParking::ocupacion($id));
        $libres = $nPlazas - $ocupadas;

        if($libres > 0){
            if(empty($daoTicket->insert($id, $matricula))){
                return 3; //Error al insertar el ticket en la base de datos
            }
            
        }
        else return 1; //Error, el usuario solo puede seleccionar parkings libres

        $ticket = $daoTicket->searchMatricula($matricula);
        $codigo = $ticket->get_codigo();
        $fecha = $ticket->get_fecha();
        $datos = [
            'codigo' => $codigo,
            'fecha' => $fecha
        ];
        return [4, $datos];
    }

    public static function plazasOcupadas($id){
        $daoTicket = TicketDAO::getSingleton();
        return $daoTicket->ocupacion($id);
    }

    public static function buscarCodigo($codigo){
        $daoTicket = TicketDAO::getSingleton();
        return $daoTicket->searchCodigo($codigo);
    }

    public static function eliminarTicket($codigo){
        $daoTicket = TicketDAO::getSingleton();
        return $daoTicket->delete($codigo);
    }

    public static function calcularImporte($ticket){
        $id = $ticket->get_id();
        $parking = SAParking::obtenerParkingPorId($id);
        if(empty($parking)){
            return 0;
        }
        $precio = $parking->getPrecio();
        $fecha = $ticket->get_fecha();
        $fecha_actual = new \DateTime();
        $res = $fecha_actual->diff($fecha);
        $dias    = $res->days;
        $horas   = $res->h;
        $minutos = $res->i;

        // Total de minutos de la estancia
        $minutosTotales = $dias * 24 * 60
                        + $horas * 60
                        + $minutos;

        // Cálculo del precio total
        $importe = $precio * $minutosTotales;

        // Formatear para mostrar
        $importe = number_format($importe, 2);
        return $importe;
    }
}
?>