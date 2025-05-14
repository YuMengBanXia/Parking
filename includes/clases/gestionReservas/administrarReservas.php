<?php

namespace es\ucm\fdi\aw\ePark;

class administrarReservas extends formBase
{

    private $dni;

    public function __construct($dni)
    {
        $this->dni = $dni;

        parent::__construct('administrarReservas');
    }

    protected function CreateFields($datos)
    {
        $reservas = SAReserva::getByDni($this->dni);

        if(empty($reservas)) {
            $html = <<<EOF
                <p>El usuario no tiene ninguna reserva pendiente</p>
            EOF;
        }
        else {
            $html = <<<EOF
            <div class="tabla-responsive">
            <table id="tablaReservas">
                <thead><tr>
                    <th>ID Parking</th>
                    <th>Fecha incio</th>
                    <th>Fecha fin</th>
                    <th>Matricula</th>
                    <th>Importe</th>
                    <th>Estado</th>
                    <th>Cancelar</th>
                    <th>Pagar</th>
                </tr></thead>
                <tbody>
            EOF;

            foreach ($reservas as $reserva) {
                $estado = htmlspecialchars($reserva->get_estado());
                $estado = strtolower($estado);

                if($estado === 'completada' || $estado === 'cancelada'){
                    continue;
                }

                $codigo = htmlspecialchars($reserva->get_codigo());
                $id = htmlspecialchars($reserva->get_id());
                $fecha_ini = htmlspecialchars($reserva->get_fecha_ini());
                $fecha_fin = htmlspecialchars($reserva->get_fecha_fin());
                $matricula = htmlspecialchars($reserva->get_matricula());
                $importe = htmlspecialchars($reserva->get_importe());
                

                $html .= <<<EOF
                <tr>
                    <td>{$id}</td>
                    <td>{$fecha_ini}</td>
                    <td>{$fecha_fin}</td>
                    <td>{$matricula}</td>
                    <td>{$importe} €</td>
                    <td>{$estado}</td>
                EOF;
                switch($estado){
                    case 'pendiente':
                        $html .=<<<EOF
                            <td><button type="button" onclick="window.location.href='cancelarReserva.php?codigo={$codigo}'">Cancelar</button></td>
                            <td><button type="submit" name="codigo" value="{$codigo}">Pagar</button></td>
                        </tr>
                        EOF;
                        break;
                    case 'pagada':
                        $html .=<<<EOF
                            <td><button type="button" onclick="window.location.href='cancelarReserva.php?codigo={$codigo}'">Cancelar</button></td>
                            <td>Ya pagada</td>
                        </tr>
                        EOF;
                        break;
                    case 'activa':
                        $html .=<<<EOF
                            <td colspan=2><button type="button" onclick="window.location.href='cancelarReserva.php?codigo={$codigo}'">Cancelar</button></td>
                            <td>Ya pagada</td>
                        </tr>
                        EOF;
                        break;
                    default:
                        $html .=<<<EOF
                            <td>No posible</td>
                            <td>No posible</td>
                        </tr>
                        EOF;
                        break;
                }
            }
            
            $html .= "</tbody>";
            $html .= "</table>";
            $html .= "</div>";
        }

        $htmlinicio = <<<EOF
            <a href="index.php">
                <button type="button">Ir al inicio</button>
            </a>
        EOF;


        return $html .= $htmlinicio;
    }


    protected function Process($datos)
    {
        $result = array();

        $codigo = trim($datos['codigo'] ?? '');
        $codigo = filter_var($codigo, FILTER_SANITIZE_NUMBER_INT);

        if(empty($codigo)) {
            $result[] = "No se ha seleccionado una reserva";
        }

        $reserva = SAReserva::getReserva($codigo);

        if(empty($reserva)){
            $result[] = "La reserva seleccionada ya no existe";
        }

        if (count($result) === 0) {
            $importe = $reserva->get_importe();
            if(empty($importe)){
                $result[] = "Ha habido un error de sistema.";
            }
            else{
                $_SESSION['pago_cantidad'] = $importe;
                $_SESSION['pago_id'] = $codigo;
                $_SESSION['pago_tipo'] = 'reserva';

                $result = "pago.php";
            }
        }

        return $result;
    }
}
?>