<?php

namespace es\ucm\fdi\aw\ePark;

class modificarForm extends formBase
{

    public function __construct()
    {

        parent::__construct('nuevoParking');
    }

    //Para la subida de archivos es necesario cambiar el enctype por defecto
    //Entonces se ha tenido que cambiar en formbase el tipo de la funcion create a protected
    protected function Create($errores = array(), &$datos = array())
    {
        $html = parent::CreateErrors($errores);

        $html .= '<form method="POST" action="' . $this->action . '" id="' . $this->formId . '" enctype="multipart/form-data">';
        $html .= '<input type="hidden" name="action" value="' . $this->formId . '" />';

        $html .= $this->CreateFields($datos);
        $html .= '</form>';

        return $html;
    }
    protected function CreateFields($datos)
    {
        if(isset($_GET['id'])){
            $html = <<<EOF
            <p>No se ha seleccionado un parking para modificar</p>
            EOF;
            return $html;
        }

        $id = $_GET['id'];
        $parking = SAParking::obtenerParkingPorId($id);

        if(empty($parking)) {
            $html = <<<EOF
            <p>El parking seleccionado no existe</p>
            EOF;
            return $html;
        }

        $dir = htmlspecialchars($parking->getDir());
        $ciudad = htmlspecialchars($parking->getCiudad());
        $precio = htmlspecialchars($parking->getPrecio());
        $nPlazas = htmlspecialchars($parking->getNPlazas());
        $cp = htmlspecialchars($parking->getCP());

        $html = <<<EOF
        <div>
            <label for="precio">Precio por minuto:</label>
            <input type="number" id="precio" name="precio" min="0.0001" max="1.0000" step="0.0001" value="{$precio}" required>
        </div>
        <div>
            <label for="dir">Dirección del parking:</label>
            <input type="text" id="dir" name="dir" value="{$dir}" required>
        </div>
        <div>
            <label for="ciudad">Ciudad:</label>
            <input type="text" id="ciudad" name="ciudad" value="{$ciudad}" required>
        </div>
        <div>
            <label for="cp">Código postal:</label>
            <input type="text" id="cp" name="cp" pattern="\d{5}" title="El código postal debe contener 5 dígitos" value="{$cp}" required>
        </div>
        <div>
            <label for="plazas">Número de plazas:</label>
            <input type="number" id="plazas" name="plazas" min="1" step="1" value="{$nPlazas}" required>
        </div>
        <div>
            <label for="img">Imagen:</label>
            <input type="file" id="img" name="img">
        </div>
        EOF;

        $html .= '<button type="submit">Actualizar</button>';
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

        $id = $_GET['id'];
        $parking = SAParking::obtenerParkingPorId($id);

        //Recoger y sanitizar datos
        $precio = trim($datos['precio'] ?? $parking->getPrecio());
        $dir = trim($datos['dir'] ?? $parking->getDir());
        $ciudad = trim($datos['ciudad'] ?? $parking->getCiudad());
        $cp = trim($datos['cp'] ?? $parking->getCP());
        $plazas = trim($datos['plazas'] ?? $parking->getNPlazas());

        $precio = filter_var($precio, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $dir = filter_var($dir, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $ciudad = filter_var($ciudad, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $cp = filter_var($cp, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $plazas = filter_var($plazas, FILTER_SANITIZE_NUMBER_INT);

        if (!preg_match('/^\d{5}$/', $cp)) {
            $result[] = "El código postal es de 5 dígitos";
        }
        
        if ($plazas < 1) {
            $result[] = "El número de plazas debe ser al menos 1";
        }

        //Procesamiento de la imagen (campo opcional)
        $img = $parking->getImg();
        if (isset($_FILES['img'])) {
            if($_FILES['img']['error'] === UPLOAD_ERR_OK){
                $uploadDir = 'img/'; 
                $imgName = basename($_FILES['img']['name']);
                $targetFile = $uploadDir . $imgName;

                // Validar que el archivo sea una imagen
                $check = getimagesize($_FILES['img']['tmp_name']);
                if ($check === false) {
                    $result[] = "El archivo subido no es una imagen válida";
                    return $result;
                }

                // Validar tamaño y extensión a 2MB
                if ($_FILES['img']['size'] > 2 * 1024 * 1024) {
                    $result[] = "El archivo es demasiado grande. El límite es 2MB";
                    return $result;
                }

                // Mover el archivo a la carpeta destino
                if (move_uploaded_file($_FILES['img']['tmp_name'], $targetFile)) {
                    if (unlink($img)) {
                        $img = $targetFile;
                    } else {
                        $result[] = "Error al eliminar la imagen.";
                    }
                } else {
                    $result[] = "Error al mover el archivo";
                }
            } else {
                $result[] = "Error en la subida de la imagen";
            }
        }

        if (count($result) === 0) {
            if(empty(SAParking::modificarParking($id,$_SESSION['dni'], $precio, $dir, $ciudad, $cp, $plazas, $img))){
                $result[] = "Error al actualizar el parking en la base de datos";
            } else {
                $result = "misParkings.php";
            }
        }

        return $result;
    }
}   