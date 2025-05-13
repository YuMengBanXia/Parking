<?php
namespace es\ucm\fdi\aw\ePark;

class SAReserva{
    public static function nuevaReserva($dni, $id, $fecha_ini, $fecha_fin, $matricula, $importe){
        $daoReserva = ReservaDAO::getSingleton();

        //La fecha de salida no puede ir antes que la fecha de entrada
        if($fecha_ini >= $fecha_fin){
            return 0; 
        }
        return $daoReserva->insert($dni, $id, $fecha_ini, $fecha_fin, $matricula, $importe);
    }

    public static function ocupacion($id){
        $daoReserva = ReservaDAO::getSingleton();
        return $daoReserva->ocupacion($id);
    }

    public static function calcularImporte($id, $fecha_ini, $fecha_fin){
        if($fecha_ini >= $fecha_fin){
            return 0; 
        }

        $parking = SAParking::obtenerParkingPorId($id);
        if(empty($parking)){
            return 0;
        }

        $precio = $parking->getPrecio();

        $segInicio = $fecha_ini->getTimestamp();
        $segFin    = $fecha_fin->getTimestamp();
        $res = $segFin - $segInicio;

        $minutosTotales = intdiv($res, 60);

        // Cálculo del precio total
        $importe = $precio * $minutosTotales;

        //Para el cálculo de los importes de las reservas, se aplica un descuento del 10% en todos los parkings
        $importe = $importe * 0.9;

        // Formatear para mostrar
        $importe = number_format($importe, 2);
        return $importe;
    }

    public static function cambiarEstado($codigo,$estado){
        $daoReserva = ReservaDAO::getSingleton();
        if(empty($daoReserva->getReserva($codigo))){
            return 0;
        }

        $estado = strtolower($estado);
        switch($estado){
            case 'pagada':
                return $daoReserva->pagar($codigo);
            case 'activa':
                return $daoReserva->activar($codigo);
            case 'cancelada':
                self::setNuevoImporte($codigo);
                return $daoReserva->cancelar($codigo);
            case 'completada':
                return $daoReserva->completar($codigo);
            default:
                return 0;
        }
    }

    public static function getAll(){
        $daoReserva = ReservaDAO::getSingleton();
        return $daoReserva->getAll();
    }

    public static function getByDni($dni){
        $daoReserva = ReservaDAO::getSingleton();
        return $daoReserva->getByDni($dni);
    }

    public static function getById($id){
        $daoReserva = ReservaDAO::getSingleton();
        return $daoReserva->getById($id);
    }

    public static function getReserva($codigo){
        $daoReserva = ReservaDAO::getSingleton();
        return $daoReserva->getReserva($codigo);
    }

    public static function comprobarDni($dni, $codigo){
        $reserva = self::getReserva($codigo);
        return $reserva->get_dni() === $dni;
    }

    public static function calcularDevolucion($reserva){
        $base = $reserva->get_importe();
        $fecha_ini = new \DateTime($reserva->get_fecha_ini());
        $fecha_ini_seg = $fecha_ini->getTimestamp();
        $fecha_seg = (new \DateTime())->getTimestamp();

        $diff = $fecha_ini_seg - $fecha_seg;

        //Calculamos la cantidad de segundos en un día (24h * 3600seg/h)
        $dia_en_seg = 24 * 3600;

        //Calculamos la cantidad de segundos en 5 horas (devolucion parcial)
        $parcial_en_seg = 5 * 3600;

        if($diff >= $dia_en_seg){
            //En caso de no haber superado un dia se devuelve todo el importe
            return $base;
        }
        elseif($diff >= $parcial_en_seg){
            //En caso de estar entre el intervalo de un día y 5 horas antes se devuelve el 50%
            return $base*0.5;
        }

        return 0;
    }

    public static function setNuevoImporte($codigo){
        $daoReserva = ReservaDAO::getSingleton();

        $reserva = self::getReserva($codigo);

        $estado = $reserva->get_estado();
        $estado = strtolower($estado);

        if($estado === 'pendiente'){
            return $daoReserva->setImporte($reserva->get_codigo(),0);
        }

        $importe_inicial = $reserva->get_importe();

        $devuelto = self::calcularDevolucion($reserva);

        $importe_final = $importe_inicial - $devuelto;

        return $daoReserva->setImporte($reserva->get_codigo(),$importe_final);
    }

    public static function setNumOrden($codigo,$num){
        $daoReserva = ReservaDAO::getSingleton();
        return $daoReserva->setNumOrden($codigo,$num);
    }

    public static function registrarPago($codigo){
        $reserva = self::getReserva($codigo);

        if(empty($reserva)){
            return 0;
        }

        $id = $reserva->get_id();
        $dni = SAParking::getDni($id);
        $importe = $reserva->get_importe();
        $fecha = new \DateTime();

        return SAPago::registrarPago($dni, $importe, $fecha);
    }
}
?>