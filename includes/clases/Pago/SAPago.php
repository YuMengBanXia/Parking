<?php
namespace es\ucm\fdi\aw\ePark;

class SAPago{
    
    */
    public static function mostrarPagos() {
        $daoPago = PagoDao::getSingleton();
        return $daoPago->getAll();
    }

    public static function obtenerPagoPorId($id) {
        $daoPago = PagoDao::getSingleton();
        return $daoPago->getById($id);
    }

    public static function registrarPago($dni,$importe,$fechaPago) {

        $daoPago = PagoDao::getSingleton();
        $pago = new TOPago(
            0,        // id provisional, se ignora en el insert
            $dni,
            $importe,
            $fechaPago
        );
        return $daoPago->insert($pago);
    }

    public static function modificarPago($id,$dni,$importe,$fechaPago) {
        
        $daoPago = PagoDao::getSingleton();
        $pago = new TOPago(
            $id,
            $dni,
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
            $resultado[] = [
                'id'        => $pago->getId(),
                'dni'       => $pago->getDni(),
                'importe'   => $pago->getImporte(),
                'fechaPago' => $pago->getFechaPago(),
            ];
        }
        return $resultados;
    }
}

?>