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

        $minIni = (new \DateTimeImmutable('+2 hours'))->format('Y-m-d\TH:i');
        $minFin = (new \DateTimeImmutable('+4 hours'))->format('Y-m-d\TH:i');

        $html = <<<EOF
        <label for="matricula">Matrícula:</label>
        <input type="text" id="matricula" name="matricula" required pattern="\d{4}[A-Za-z]{3}" 
        title="Introduce una matrícula válida: 4 dígitos seguidos de 3 letras (ej. 1234ABC)" value="{$matricula}">
        <br><br>
        <label for="fecha_ini">Fecha y hora de inicio</label>
        <input type="datetime-local" id="fecha_ini" name="fecha_ini" required
            min="{$minIni}">
        <br><br>
        <label for="fecha_fin">Fecha y hora de fin</label>
        <input type="datetime-local" id="fecha_fin" name="fecha_fin" required
            min="{$minFin}">
        
        <div class="tabla-responsive">
        <table id="tablaParkings">
            <thead><tr>
                <th>ID</th>
                <th>Imagen</th>
                <th>Dirección</th>
                <th>Ciudad</th>
                <th>Precio</th>
                <th>Escoger</th>
            </tr></thead>
            <tbody>
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
                <td>{$id}</td>
                <td><img src="{$img}" alt="Imagen del parking"></td>
                <td>{$dir}</td>
                <td>{$ciudad}</td>
                <td>{$precio} €</td>
                <td>
                    <label><input type="radio" name="parking_id" value="{$id}" {$checked}></label>
                </td>
            </tr>
            EOF;
        }

        $html .= "</tbody>";
        $html .= "</table>";
        $html .="</div>";

        $html .= <<<EOF
            <label for="politica">
                <input type="checkbox" id="politica" name="politica" class="form-check-input" required>
                He leído y acepto la <a href="politicaCancelacion.php" target="_blank">Política de Cancelación</a>
            </label>
            <button type="submit"">Confirmar e ir a pagar</button>'
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
        $dni = trim($this->dni ?? '');
        $fecha_ini = trim($datos['fecha_ini'] ?? '');
        $fecha_fin = trim($datos['fecha_fin'] ?? '');

        $matricula = filter_var($matricula, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $dni = filter_var($dni, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $fecha_ini = filter_var($fecha_ini, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $fecha_fin = filter_var($fecha_fin, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $fecha_ini = \DateTimeImmutable::createFromFormat('Y-m-d\TH:i', $fecha_ini);
        $fecha_fin = \DateTimeImmutable::createFromFormat('Y-m-d\TH:i', $fecha_fin);

        
        $politica = isset($_POST['politica']);

        if ($matricula === '') {
            $result[] = "La matrícula del coche no puede estar vacío";
        }

        if ($id === '') {
            $result[] = "Debe seleccionar un parking";
        }

        if($dni === ''){
            $result[] = "Ha habido un problema con el procesamiento del dni del usuario";
        }

        if(empty($fecha_ini) || empty($fecha_fin)){
            $result[] = "Debe seleccionarse una fecha";
        }
        else{
            if($fecha_ini > $fecha_fin){
                $result[] = "La fecha final debe ser posterior a la fecha final";
            }
            else{
                $secs = $fecha_fin->getTimestamp() - $fecha_ini->getTimestamp();
                if ($secs < 2 * 3600) {
                    $result[] = 'La reserva debe durar al menos 2 horas';
                }
            }
        }
        
        if(empty($politica)){
            $result[] = "Es necesario aceptar la politica de cancelacion";
        }

        if (count($result) === 0) {
            $importe = SAReserva::calcularImporte($id, $fecha_ini, $fecha_fin);
            if(empty($importe)){
                $result[] = "Ha habido un error de sistema.";
            }
            else{
                $fecha_ini = $fecha_ini->format('Y-m-d H:i:s');
                $fecha_fin = $fecha_fin->format('Y-m-d H:i:s');
                $codigo = SAReserva::nuevaReserva($dni, $id, $fecha_ini, $fecha_fin, $matricula, $importe);
                if(empty($codigo)){
                    $result[] = "Ha habido un error de sistema.";
                }
                else{
                    $_SESSION['pago_cantidad'] = $importe;
                    $_SESSION['pago_id'] = $codigo;
                    $_SESSION['pago_tipo'] = 'reserva';
    
                    $result = "pago.php";
                }
            }  
        }

        return $result;
    }
}