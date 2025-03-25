<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ ."/../vistas/comun/formBase.php";
require_once __DIR__."/ticket/SATicket.php";


class mostrarParkingsForm extends formBase {
    private $mostrar=1;


    public function __construct()
    {
    
        parent::__construct('mostrarParkingsForm');
        
    }
   
    protected function CreateFields($datos)
    {
        $matricula = htmlspecialchars($datos['matricula'] ?? '');
        $idSeleccionado = $datos['parking_id'] ?? '';
        $parkings = SAParking::mostrarParkingsLibres();
        
    
        $html = <<<EOF
        <label for="matricula">Matrícula:</label>
        <input type="text" id="matricula" name="matricula" required pattern="\d{4}[A-Za-z]{3}" 
        title="Introduce una matrícula válida: 4 dígitos seguidos de 3 letras (ej. 1234ABC)" value="{$matricula}">

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
            $dir = htmlspecialchars($parking->getDir());
            $ciudad = htmlspecialchars($parking->getCiudad());
            $precio = htmlspecialchars($parking->getPrecio());
            $nPlazas = htmlspecialchars($parking->getNPlazas());
    
            $checked = ($idSeleccionado == $id) ? 'checked' : '';
    
            $html .= <<<EOF
            <tr>
                <td>{$id}</td>
                <td>{$dir}</td>
                <td>{$ciudad}</td>
                <td>{$precio} €</td>
                <td>{$nPlazas}</td>
                <td>
                    <label><input type="radio" name="parking_id" value="{$id}" {$checked}></label>
                </td>
            </tr>
            EOF;
        }
    
        $html .= "</table>";
        $html .= '<button type="submit">Confirmar</button>';
        $htmlinicio=<<<EOF
            <a href="index.php">
                <button type="button">Ir al inicio</button>
            </a>
        EOF;

        if($this->mostrar==0){
            return $htmlinicio;
        }
        return $html.=$htmlinicio;
    }
    
    
    protected function Process( $datos ){
        $result = array();
        
        $matricula = trim($datos['matricula'] ?? '');
        $id=trim($datos['parking_id'] ?? '');

        $matricula = filter_var($matricula, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $id = filter_var($id, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if ($matricula==='') {
            $result[] = "La matrícula del coche no puede estar vacío";
            return $result;
        }

        if($id===''){
            $result[]="Debe seleccionar un parking";
            return $result;
        }

        if (count($result) === 0){
            $respuesta = SATicket::nuevoTicket($id,$matricula);
            if (is_array($respuesta)) {
                // Éxito: el código es $resultado[0] (que será 4) 
                // y $resultado[1] contiene los datos del ticket.
                $num = $respuesta[0];
                $datos = $respuesta[1];
            } else {
                // Error: $resultado es numérico (0,1,2,3) y representa el código de error.
                $num = $respuesta;
            }

            switch($num){
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
                    $result[]="Su ticket con el código:$codigo y matrícula:$matricula se ha generado corectamente. Fecha de inicio: $fecha";
                    $this->mostrar=0;
                    break;
                default: //caso 0 (faltan datos) o error inesperado
                    $result[] = 'Ha habido un error inesperado vuelva a intentarlo';
                    break;
            }
        }      
        
        return $result;

    }


}
