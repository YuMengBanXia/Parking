<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__.'/ticket/SATicket.php';


class procesarTicket{

  
    public static function generar(){
        $resultado = SATicket::nuevoTicket($_POST['parking_id'],$_POST['matricula']);
        if (is_array($resultado)) {
            // Éxito: el código es $resultado[0] (que será 4) 
            // y $resultado[1] contiene los datos del ticket.
            $num = $resultado[0];
            $datos = $resultado[1];
        } else {
            // Error: $resultado es numérico (0,1,2,3) y representa el código de error.
            
            $num = $resultado;
        }

        $html = '';

        switch($num){
            case 1: //Datos no especificados
                $html .= '<h4>Introduzca todos los datos requeridos por favor</h4>';
                $html .= self::incluirVistaParkingLibres();
                break;
            case 2: //Matrícula ya encontrada dentro de un parking
                $html .= '<h4>La matrícula introducida ha sido encontrada en otro parking</h4>';
                $html .= self::incluirVistaParkingLibres();
                break;
            case 3: //Error base de datos
                $html .= '<h4>Ha habido un error en la base de datos</h4>';
                $html .= self::incluirVistaParkingLibres();
                break;
            case 4: //Éxito
                $codigo = $datos['codigo'];
                $fecha = $datos['fecha']->format('d-m-Y H:i:s');
                $html .= <<<EOF
                    <h4>Ticket sacado con éxito! Su código es $codigo y se ha sacado el $fecha</h4>
                EOF;  
                break;
            default: //caso 0 o suceso inesperado
                $html .= '<h4>Ha habido un error inesperado vuelva a intentarlo</h4>';
                $html .= self::incluirVistaParkingLibres();
                break;
        }

        unset($_POST['parking_id'],$_POST['matricula']);

        return $html;
    }

    private static function incluirVistaParkingLibres() {
        ob_start();
        require __DIR__ . '/scriptParkingsLibres.php';
        return ob_get_clean();
    }

}
?>