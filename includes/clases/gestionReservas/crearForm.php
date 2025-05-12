<?php

namespace es\ucm\fdi\aw\ePark;

class crearForm extends formBase
{
    private $dni;

    public function __construct($dni)
    {
        $this->dni = $dni;

        parent::__construct('crearReserva');
    }

    protected function CreateFields($datos)
    {
        $matricula = htmlspecialchars($datos['matricula'] ?? '');
        $idSeleccionado = $datos['parking_id'] ?? '';
        $parkings = SAParking::mostrarParkings();

        $minIni = (new \DateTimeImmutable())->format('Y-m-d\TH:i');
        $minFin = (new \DateTimeImmutable('+1 hour'))->format('Y-m-d\TH:i');

        $html = <<<EOF
        <label for="matricula">Matrícula:</label>
        <input type="text" id="matricula" name="matricula" required pattern="\d{4}[A-Za-z]{3}" 
        title="Introduce una matrícula válida: 4 dígitos seguidos de 3 letras (ej. 1234ABC)" value="{$matricula}">
        <label for="fecha_ini">Fecha y hora de inicio</label>
        <input type="datetime-local" id="fecha_ini" name="fecha_ini" required
            min="{$minIni}">
        <label for="fecha_fin">Fecha y hora de fin</label>
        <input type="datetime-local" id="fecha_fin" name="fecha_fin" required
            min="{$minFin}">
        
        <div class="tabla-responsive">
        <table>
            <tr>
                <th>Imagen</th>
                <th>ID</th>
                <th>Dirección</th>
                <th>Ciudad</th>
                <th>Precio</th>
                <th>Escoger</th>
            </tr>
        EOF;

        foreach ($parkings as $parking) {
            $id = htmlspecialchars($parking->getId());

            $dir = htmlspecialchars($parking->getDir());
            $ciudad = htmlspecialchars($parking->getCiudad());
            $precio = htmlspecialchars($parking->getPrecio());

            $checked = ($idSeleccionado == $id) ? 'checked' : '';

            $imgPath = (!empty($parking->getImg()) && file_exists($parking->getImg())) ? $parking->getImg() : "img/default.png";
            $img = htmlspecialchars($imgPath);

            $html .= <<<EOF
            <tr>
                <td><img src="{$img}" alt="Imagen del parking"></td>
                <td>{$id}</td>
                <td>{$dir}</td>
                <td>{$ciudad}</td>
                <td>{$precio} €</td>
                <td>
                    <label><input type="radio" name="parking_id" value="{$id}" {$checked}></label>
                </td>
            </tr>
            EOF;
        }

        $html .= "</table>";
        $html .="</div>";
        $html .= <<<EOF
            <button type="submit" name="dni" value="{$this->dni}">Confirmar</button>'
        EOF;
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
        $dni = trim($datos['dni'] ?? '');
        $fecha_ini = trim($datos['fecha_ini'] ?? '');
        $fecha_fin = trim($datos['fecha_fin'] ?? '');

        $matricula = filter_var($matricula, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $dni = filter_var($dni, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $fecha_ini = filter_var($fecha_ini, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $fecha_fin = filter_var($fecha_fin, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if ($matricula === '') {
            $result[] = "La matrícula del coche no puede estar vacío";
        }

        if ($id === '') {
            $result[] = "Debe seleccionar un parking";
        }

        if($dni === ''){
            $result[] = "Ha habido un problema con el procesamiento del dni del usuario";
        }

        if($fecha_ini === '' || $fecha_fin === ''){
            $result[] = "Debe seleccionarse una fecha";
        }

        if (count($result) === 0) {

        }

        return $result;
    }
}