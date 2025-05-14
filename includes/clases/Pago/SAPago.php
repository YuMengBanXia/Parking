<?php
namespace es\ucm\fdi\aw\ePark;

class SAPago{
    public static function mostrarPagos() {
        $daoPago = PagoDao::getSingleton();
        return $daoPago->getAll();
    }

    public static function obtenerPagoPorId($id) {
        $daoPago = PagoDao::getSingleton();
        return $daoPago->getById($id);
    }

    public static function registrarPago($dni,$idParking,$importe,$fechaPago) {

        $daoPago = PagoDao::getSingleton();
        $pago = new TOPago(
            0,        // id provisional, se ignora en el insert
            $dni,
            $idParking,   
            $importe,
            $fechaPago
        );
        return $daoPago->insert($pago);
    }

    public static function modificarPago($id,$dni,$idParking,$importe,$fechaPago) {
        
        $daoPago = PagoDao::getSingleton();
        $pago = new TOPago(
            $id,
            $dni,
            $idParking,
            $importe,
            $fechaPago
        );
        return $daoPago->update($pago);
    }

    public static function eliminarPago($id) {
        $daoPago= PagoDao::getSingleton();
        return $daoPago->delete($id);
    }


    public static function listarPorRangoFecha($desde,$hasta){

        $daoPago = PagoDao::getSingleton();
        $objetos = $daoPago->listarPorRangoFecha($desde,$hasta);

        $resultados = [];

        foreach ($objetos as $pago) {
            $resultados[] = [
                'id'        => $pago->getId(),
                'dni'       => $pago->getDni(),
                'importe'   => $pago->getImporte(),
                'fechaPago' => $pago->getFechaPago(),
            ];
        }
        return $resultados;
    }

    public static function listarPorRangoFechaYPropietario(
        string $desde,
        string $hasta,
        string $dniPropietario
    ): array {
        $dao     = PagoDao::getSingleton();
        $objetos = $dao->listarPorRangoFechaYPropietario(
            $desde, $hasta, $dniPropietario
        );
        $res     = [];
        foreach ($objetos as $p) {
            $res[] = [
                'id'         => $p->getId(),
                'dni'        => $p->getDni(),
                'idParking'  => $p->getIdParking(),
                'importe'    => $p->getImporte(),
                'fechaPago'  => $p->getFechaPago(),
            ];
        }
        return $res;
    }
}

?>