<?php

namespace es\ucm\fdi\aw\ePark;

class cogerTicket extends formBase
{

    public function __construct()
    {

        parent::__construct('cogerTicket');
    }

    protected function CreateFields($datos)
    {
        $matricula = htmlspecialchars($datos['matricula'] ?? '');
        $idSeleccionado = $datos['parking_id'] ?? '';
        $parkings = SAParking::mostrarParkings();


        $html = <<<EOF
        <label for="matricula">Matrícula:</label>
        <input type="text" id="matricula" name="matricula" required pattern="\d{4}[A-Za-z]{3}" 
        title="Introduce una matrícula válida: 4 dígitos seguidos de 3 letras (ej. 1234ABC)" value="{$matricula}">
        <div class="tabla-responsive">
        <table>
            <tr>
                <th>ID</th>
                <th>Dirección</th>
                <th>Ciudad</th>
                <th>Precio</th>
                <th>Plazas Disponibles</th>
                <th>Escoger</th>
            </tr>
        EOF;

        foreach ($parkings as $parking) {
            $id = htmlspecialchars($parking->getId());
            $nPlazas = htmlspecialchars($parking->getNPlazas());

            //Comprobamos si tiene plazas libres
            if(empty(SATicket::libre($id, $nPlazas))) {
                continue;
            }

            $dir = htmlspecialchars($parking->getDir());
            $ciudad = htmlspecialchars($parking->getCiudad());
            $precio = htmlspecialchars($parking->getPrecio());
            $ocupadas = htmlspecialchars(SATicket::plazasOcupadas($id));
            $libres = $nPlazas - $ocupadas;

            $checked = ($idSeleccionado == $id) ? 'checked' : '';

            $html .= <<<EOF
            <tr>
                <td>{$id}</td>
                <td>{$dir}</td>
                <td>{$ciudad}</td>
                <td>{$precio} €</td>
                <td>{$libres}</td>
                <td>
                    <label><input type="radio" name="parking_id" value="{$id}" {$checked}></label>
                </td>
            </tr>
            EOF;
        }

        $html .= "</table>";
        $html .="</div>";
        $html .= '<button type="submit">Confirmar</button>';
        $htmlinicio = <<<EOF
            <a href="index.php" class="btn-link">Ir al inicio</a>
        EOF;


        return $html .= $htmlinicio;
    }


    protected function Process($datos)
    {
        $result = array();

        $matricula = trim($datos['matricula'] ?? '');
        $id = trim($datos['parking_id'] ?? '');

        $matricula = filter_var($matricula, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $id = filter_var($id, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if ($matricula === '') {
            $result[] = "La matrícula del coche no puede estar vacío";
        }

        if ($id === '') {
            $result[] = "Debe seleccionar un parking";
        }

        if (count($result) === 0) {
            $respuesta = SATicket::nuevoTicket($id, $matricula);
            if (is_array($respuesta)) {
                // Éxito: el código es $resultado[0] (que será 4) 
                // y $resultado[1] contiene los datos del ticket.
                $num = $respuesta[0];
                $datos = $respuesta[1];
            } else {
                // Error: $resultado es numérico (0,1,2,3) y representa el código de error.
                $num = $respuesta;
            }

            switch ($num) {
                case 0:
                    $result[] = "Faltan datos por seleccionar";
                    break;
                case 1: //Errores con el dato id
                    $result[] = 'Algo ha salido mal, por favor vuelva a seleccionar un parking disponible';
                    break;
                case 2: //Matrícula ya encontrada dentro de un parking
                    $result[] = 'La matrícula introducida ha sido encontrada en otro parking';
                    break;
                case 3: //Error base de datos
                    $result[] = 'Ha habido un error en la base de datos';
                    break;
                case 4: //Éxito
                    $codigo = $datos['codigo'];
                    $fecha = $datos['fecha']->format('d-m-Y H:i:s');

                    $params = http_build_query([
                        'ticket' => $codigo,
                        'matricula' => $matricula,
                        'fecha' => $fecha
                    ]);

                    $result = 'ticketCogido.php?' . $params;

                    break;
                default: //caso 0 (faltan datos) o error inesperado
                    $result[] = 'Ha habido un error inesperado vuelva a intentarlo';
                    break;
            }
        }

        return $result;
    }
}
