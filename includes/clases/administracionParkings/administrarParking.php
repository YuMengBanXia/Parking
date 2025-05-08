<?php

namespace es\ucm\fdi\aw\ePark;

class administrarParking extends formBase
{

    private $dni;

    public function __construct($dni)
    {
        $this->dni = $dni;

        parent::__construct('administrarParking');
    }

    protected function CreateFields($datos)
    {
        $parkings = $this->getParkings($this->dni);

        if(empty($parkings)) {
            $html = <<<EOF
                <p>No hay parkings registrados</p>
            EOF;
        }
        else {
            $html = <<<EOF
            <table>
                <tr>
                    <th>Imagen</th>
                    <th>ID</th>
                    <th>Dirección</th>
                    <th>Ciudad</th>
                    <th>CP</th>
                    <th>Precio</th>
                    <th>Plazas</th>
                    <th>Ocupadas</th>
                    <th>Actualizar</th>
                    <th>Eliminar</th>
                </tr>
            EOF;

            foreach ($parkings as $parking) {
                $id = htmlspecialchars($parking->getId());
                $dir = htmlspecialchars($parking->getDir());
                $ciudad = htmlspecialchars($parking->getCiudad());
                $precio = htmlspecialchars($parking->getPrecio());
                $nPlazas = htmlspecialchars($parking->getNPlazas());

                $imgPath = (!empty($parking->getImg()) && file_exists($parking->getImg())) ? $parking->getImg() : "img/default.png";
                $img = htmlspecialchars($imgPath);
                
                $ocupadas = htmlspecialchars(SATicket::plazasOcupadas($id));
                $cp = htmlspecialchars($parking->getCP());

                

                $html .= <<<EOF
                <tr>
                    <td><img src="{$img}" alt="Imagen del parking"></td>
                    <td>{$id}</td>
                    <td>{$dir}</td>
                    <td>{$ciudad}</td>
                    <td>{$cp}</td>
                    <td>{$precio} €</td>
                    <td>{$nPlazas}</td>
                    <td>{$ocupadas}</td>
                    <td><button type="button" onclick="window.location.href='modificarParking.php?id={$id}'">Modificar</button></td>
                    <td><button type="submit" name="id" value="{$id}">Eliminar</button></td>
                </tr>
                EOF;
            }

            $html .= "</table>";
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

        $id = trim($datos['id'] ?? '');
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

        if(empty($id)) {
            $result[] = "No se ha seleccionado un id de parking";
        }

        if(count($result) == 0) {
            $parking = SAParking::obtenerParkingPorId($id);
            $img = $parking->getImg();
            if(!empty($img)){
                if(!unlink($img)){
                    $result[] = "Error al eliminar la imagen";
                    return $result;
                }
            }

            SAParking::eliminarParking($id);
            $result = "misParkings.php";
        }

        return $result;
    }

    protected function getParkings($dni){//función a sobreescribir
        return null;
    }
}
