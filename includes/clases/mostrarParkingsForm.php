<?php

include __DIR__ ."/../vistas/comun/formBase.php";
include __DIR__ ."/parking/SAParking.php";

class mostrarParkingsForm extends formBase {

    public function __construct() {
        parent::__construct('mostrarParkingsForm');
    }

    protected function CreateFields($datos)
    {

        // Obtener el array de parkingsLibres 
        $parkings = SAParking::mostrarParkingsLibres();

        // Inicio de la tabla
        $html = <<<EOF
        <table>
            <tr>
                <th>ID</th>
                <th>Dirección</th>
                <th>Ciudad</th>
                <th>Precio</th>
                <th>Plazas Disponibles</th>
                <th>Acción</th>
            </tr>
        EOF;

        // Bucle para recorrer los parkings
        foreach ($parkings as $parking) {
            $id = htmlspecialchars($parking->getId());
            $dir = htmlspecialchars($parking->getDir());
            $ciudad = htmlspecialchars($parking->getCiudad());
            $precio = htmlspecialchars($parking->getPrecio());
            $nPlazas = htmlspecialchars($parking->getNPlazas());

            // Concatenar cada fila de la tabla
            $html .= <<<EOF
            <tr>
                <td>{$id}</td>
                <td>{$dir}</td>
                <td>{$ciudad}</td>
                <td>{$precio} €</td>
                <td>{$nPlazas}</td>
                <td>
                    <form method="post" action="ticket.php">
                        <input type="hidden" name="parking_id" value="{$id}">
                        <button type="submit">Seleccionar</button>
                    </form>
                </td>
            </tr>
            EOF;
        }

        // Cierre de la tabla
        $html .= <<<EOF
        </table>
        EOF;

        return $html;
    }
}
