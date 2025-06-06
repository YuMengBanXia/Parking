<?php
namespace es\ucm\fdi\aw\ePark;

class pagarForm extends formBase {
    public function __construct()
    {   
        parent::__construct('pagarForm');
    }

    protected function CreateFields($datos){
        $matricula = htmlspecialchars($datos['matricula'] ?? '');

        $html = <<<EOF
        <div>
            <p>Introduzca uno de los campos para poder identificar su ticket</p>
        </div>
        <div>
        <label for="matricula">Matrícula:</label>
            <input type="text" id="matricula" name="matricula" pattern="\d{4}[A-Za-z]{3}" 
            title="Introduce una matrícula válida: 4 dígitos seguidos de 3 letras (ej. 1234ABC)" value="{$matricula}">
        </div>
        <div>
            <label for="codigo">Codigo del ticket</label>
            <input type="number" id="codigo" name="codigo" min="1" step="1">
        </div>
        <button type="submit">Buscar ticket</button>
        EOF;

        $htmlinicio = <<<EOF
            <a href="index.php">
                <button type="button">Ir al inicio</button>
            </a>
        EOF;


        return $html .= $htmlinicio;
    }

    protected function Process($datos){
        $result = array();

        //Recoger y sanitizar datos
        $matricula = trim($datos['matricula'] ?? '');
        $matricula = filter_var($matricula, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $codigo = trim($datos['codigo'] ?? '');
        $codigo = filter_var($codigo, FILTER_SANITIZE_NUMBER_INT);

        //Comprobamos que alguno de los campos ha sido rellenado
        if($matricula === '' && $codigo === ''){
            $result[] = "Es necesario rellenar uno de los dos campos para poder realizar la busqueda de su ticket";
            return $result;
        }

        if(!empty($matricula)){
            $ticket = SATicket::buscarMatricula($matricula);
            if(empty($ticket)){
                $result[] = "No se encontro el ticket asociado a la matricula introducida";
            }
        }
        else{
            $ticket = SATicket::buscarCodigo($codigo);
            if(empty($ticket)){
                $result[] = "El codigo no corresponde con ningun ticket del sistema";
            }
        }

        if (count($result) === 0) {
            $importe = SATicket::calcularImporte($ticket);
            if(empty($importe)){
                $result[] = "Ha habido un error de sistema.";
            }
            else{
                $_SESSION['pago_cantidad'] = $importe;
                $codigo = $ticket->get_codigo();
                $_SESSION['pago_id'] = $codigo;
                $_SESSION['pago_tipo'] = 'ticket';

                $result = "pago.php";
            }
        }

        return $result;
    }
}
?>