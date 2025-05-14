<?php

namespace es\ucm\fdi\aw\ePark;

class cancelarForm extends formBase{
    private $reserva;

    public function __construct($reserva){

        $this->reserva = $reserva;

        parent::__construct('cancelarReserva');
    }

    protected function CreateFields($datos){
        if(empty($this->reserva)){
            $html = <<<EOF
                <p>Ha ocurrido un error procesando la reserva</p>
            EOF;
        }
        else{
            $html = <<<EOF
                <table>
                    <tr>
                        <th>ID Parking</th>
                        <th>Fecha incio</th>
                        <th>Fecha fin</th>
                        <th>Matricula</th>
                        <th>Importe</th>
                        <th>Estado</th>
                    </tr>
            EOF;

            $codigo = htmlspecialchars($this->reserva->get_codigo());
            $id = htmlspecialchars($this->reserva->get_id());
            $fecha_ini = htmlspecialchars($this->reserva->get_fecha_ini());
            $fecha_fin = htmlspecialchars($this->reserva->get_fecha_fin());
            $matricula = htmlspecialchars($this->reserva->get_matricula());
            $importe = htmlspecialchars($this->reserva->get_importe());
            $estado = htmlspecialchars($this->reserva->get_estado());
            $estado = strtolower($estado);

            $html .= <<<EOF
                    <tr>
                        <td>{$id}</td>
                        <td>{$fecha_ini}</td>
                        <td>{$fecha_fin}</td>
                        <td>{$matricula}</td>
                        <td>{$importe} €</td>
                        <td>{$estado}</td>
                    </tr>
                </table>
            EOF;

            switch($estado){
                case 'pagada':
                    $html .=<<<EOF
                        <p>Está seguro de que quiere cancelar la reserva?</p>
                        <p>El importe devuelto se calculará según la <a href='politicaCancelacion.php' target="_blank">Política de Cancelación</a></p>
                        <button type="submit" name="codigo" value="{$codigo}">Confirmar cancelacion</button>
                    EOF;
                    break;
                case 'completada':
                    $html .=<<<EOF
                        <p>La reserva ya fue completada</p>
                    EOF;
                    break;
                case 'cancelada':
                    $html .= <<<EOF
                        <p>La reserva ya ha sido cancelada</p>
                    EOF;
                    break;
                case 'activa':
                    $html .=<<<EOF
                        <p>Está seguro de que quiere cancelar la reserva?<p>
                        <p>No se devolvera el importe pagado como esta descrito en la <a href='politicaCancelacion.php' target="_blank">Política de Cancelación</a></p>
                        <button type="submit" name="codigo" value="{$codigo}">Confirmar cancelacion</button>
                    EOF;
                    break;
                default:
                    $html .=<<<EOF
                        <p>Está seguro de que quiere cancelar la reserva?<p>
                        <button type="submit" name="codigo" value="{$codigo}">Confirmar cancelacion</button>
                    EOF;
                    break;
            }
        }

        $htmlinicio = <<<EOF
            <a href="index.php">
                <button type="button">Ir al inicio</button>
            </a>
        EOF;


        return $html .= $htmlinicio;
    }

    protected function Process($datos){

        $result = array();

        $estado = htmlspecialchars($this->reserva->get_estado());
        $estado = strtolower($estado);

        if(empty($this->reserva)){
            $result[] = "Ha sucedido un error con la reserva seleccionada";
        }

        if (count($result) === 0) {
            $codigo = htmlspecialchars($this->reserva->get_codigo());
            $id = htmlspecialchars($this->reserva->get_id());
            $fecha_ini = htmlspecialchars($this->reserva->get_fecha_ini());
            $matricula = htmlspecialchars($this->reserva->get_matricula());
            $importe = SAReserva::calcularDevolucion($this->reserva);

            $params = http_build_query([
                'codigo' => $codigo,
                'id' => $id,
                'matricula' => $matricula,
                'fecha_ini' => $fecha_ini,
                'importe' => $importe
            ]);

            switch($estado){
                case 'pagada':
                    //Se ha sobrepasado las 5 horas de antelacion
                    if($importe === 0){
                        SAReserva::cambiarEstado($codigo, 'cancelada');
                        SAReserva::registrarPago($codigo);
                        $result = 'reservaCancelada.php?' . $params;
                    }
                    else{
                        $num_orden = $this->reserva->get_num_orden();
                        if(empty($num_orden)){
                            $result[] = "Numero de orden no encontrada";
                        }
                        else{
                            $_SESSION['pago_cantidad'] = $importe;
                            $_SESSION['pago_id'] = $codigo;
                            $_SESSION['num_orden'] = $num_orden;

                            $result = "devolucion.php";
                        }
                    }
                    break;
                case 'pendiente':
                    SAReserva::cambiarEstado($codigo, 'cancelada');
                    $result = 'reservaCancelada.php?' . $params;
                    break;
                case 'activa':
                    SAReserva::cambiarEstado($codigo, 'cancelada');
                    SAReserva::registrarPago($codigo);
                    $result = 'reservaCancelada.php?' . $params;
                    break;
                default:
                    $result[] = "La reserva no puede ser cancelada";
                    break;
            }
        }

        return $result;
    }
}
?>