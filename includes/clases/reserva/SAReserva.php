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
        $res = $fecha_fin->diff($fecha_ini);
        $dias    = $res->days;
        $horas   = $res->h;
        $minutos = $res->i;

        // Total de minutos de la estancia
        $minutosTotales = $dias * 24 * 60
                        + $horas * 60
                        + $minutos;

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
}
?>