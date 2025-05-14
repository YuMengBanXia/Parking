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
        <table id="tablaParkings">
            <thead><tr>
                <th>ID</th>
                <th>Imagen</th>
                <th>Dirección</th>
                <th>Ciudad</th>
                <th>Precio €</th>
                <th>Plazas Disponibles</th>
                <th>Distancia</th>
                <th>Escoger</th>
            </thead></tr>
        EOF;

        foreach ($parkings as $parking) {
            $id = htmlspecialchars($parking->getId());
            $nPlazas = htmlspecialchars($parking->getNPlazas());
            $ocupadas = htmlspecialchars(SAParking::ocupacion($id));
            $libres = $nPlazas - $ocupadas;

            if($libres <= 0) continue;

            $dir = htmlspecialchars($parking->getDir());
            $ciudad = htmlspecialchars($parking->getCiudad());
            $precio = htmlspecialchars($parking->getPrecio());
            $direccionCompleta = "$dir, $ciudad";

            $checked = ($idSeleccionado == $id) ? 'checked' : '';
            $imgPath = (!empty($parking->getImg()) && file_exists($parking->getImg())) ? $parking->getImg() : "img/default.png";
            $img = htmlspecialchars($imgPath);

            $html .= <<<EOF
            <tr data-direccion="{$direccionCompleta}">
                <td>{$id}</td>
                <td><img src="{$img}" alt="Imagen del parking" style="max-width: 100px;"></td>
                <td>{$dir}</td>
                <td>{$ciudad}</td>
                <td>{$precio}</td>
                <td>{$libres}</td>
                <td class="distancia">Calculando...</td>
                <td>
                    <label><input type="radio" name="parking_id" value="{$id}" {$checked}></label>
                </td>
            </tr>
            EOF;
        }

        $html .= "</table></div>";
        $html .= '<button type="submit" class="btn-confirmar">Confirmar</button>';
        $html .= '<a href="index.php" class="btn-link">Ir al inicio</a>';

        // Inclusión del archivo JS externo con atribución a OpenStreetMap

        $rutaJS = RUTA_JS;
        $html .= <<<EOF
        <script src="{$rutaJS}/calculoDistancias.js" defer></script>
        <footer class="osm-attribution">
             <a href="https://www.openstreetmap.org/copyright" target="_blank">© OpenStreetMap</a>
        </footer>
        EOF;

        return $html;
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
                $num = $respuesta[0];
                $datos = $respuesta[1];
            } else {
                $num = $respuesta;
            }

            switch ($num) {
                case 0:
                    $result[] = "Faltan datos por seleccionar";
                    break;
                case 1:
                    $result[] = 'Algo ha salido mal, por favor vuelva a seleccionar un parking disponible';
                    break;
                case 2:
                    $result[] = 'La matrícula introducida ha sido encontrada en otro parking';
                    break;
                case 3:
                    $result[] = 'Ha habido un error en la base de datos';
                    break;
                case 4:
                    $codigo = $datos['codigo'];
                    $fecha = $datos['fecha']->format('d-m-Y H:i:s');

                    $params = http_build_query([
                        'ticket' => $codigo,
                        'matricula' => $matricula,
                        'fecha' => $fecha
                    ]);

                    $result = 'ticketCogido.php?' . $params;
                    break;
                default:
                    $result[] = 'Ha habido un error inesperado vuelva a intentarlo';
                    break;
            }
        }

        return $result;
    }
}