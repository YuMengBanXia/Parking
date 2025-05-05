<?php

namespace es\ucm\fdi\aw\ePark;

class modificarForm extends formBase
{

    private $parking;

    public function __construct($parking)
    {   
        $this->parking = $parking;

        parent::__construct('modificarParking');
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
        $dir = htmlspecialchars($this->parking->getDir());
        $ciudad = htmlspecialchars($this->parking->getCiudad());
        $precio = htmlspecialchars($this->parking->getPrecio());
        $nPlazas = htmlspecialchars($this->parking->getNPlazas());
        $cp = htmlspecialchars($this->parking->getCP());
        $id = htmlspecialchars($this->parking->getId());

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
            <label for="imgInput">Imagen:</label>
            <input type="file" id="imgInput" name="img" accept="image/*">
            <img class="imgPreview" alt="Vista previa de la imagen">
            <div class="imgError"></div>
        </div>
        <button type="submit" name="id" value="{$id}">Actualizar</button>
        EOF;

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

        //Recoger y sanitizar datos
        $id =  trim($datos['id'] ?? '');
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

        $parking = SAParking::obtenerParkingPorId($id);

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
                $extension = pathinfo($imgName, PATHINFO_EXTENSION);
                $uniqueName = uniqid('img_', true) . '.' . $extension;
                $targetFile = $uploadDir . $uniqueName;

                // Validar que el archivo sea una imagen
                $check = getimagesize($_FILES['img']['tmp_name']);
                if ($check === false) {
                    unset($_FILES['img']);
                    $result[] = "El archivo subido no es una imagen válida";
                    return $result;
                }

                // Validar tamaño y extensión a 2MB
                if ($_FILES['img']['size'] > 2 * 1024 * 1024) {
                    unset($_FILES['img']);
                    $result[] = "El archivo es demasiado grande. El límite es 2MB";
                    return $result;
                }

                // Mover el archivo a la carpeta destino
                if (move_uploaded_file($_FILES['img']['tmp_name'], $targetFile)) {
                    if(!empty($img)){
                        if (unlink($img)) {
                            $img = $targetFile;
                        } else {
                            unset($_FILES['img']);
                            $result[] = "Error al eliminar la antigua imagen.";
                        }
                    }
                    else{
                        $img = $targetFile;
                    }
                    
                } else {
                    unset($_FILES['img']);
                    $result[] = "Error al mover el archivo";
                }
            } 
            elseif($_FILES['img']['error'] === UPLOAD_ERR_NO_FILE){
                //No pasa nada porque significa que no se ha subido un archivo
            }
            else {
                unset($_FILES['img']);
                $result[] = "Error en la subida de la imagen";
            }
        }

        if (count($result) === 0) {
            if(empty(SAParking::modificarParking($id, $precio, $dir, $ciudad, $cp, $plazas, $img))){
                $result[] = "Error al actualizar el parking en la base de datos";
            } else {
                $result = "misParkings.php";
            }
        }

        return $result;
    }
}   