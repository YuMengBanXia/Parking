<?php

namespace es\ucm\fdi\aw\ePark;

class nuevoParking extends formBase
{

    private $dni;

    public function __construct($dni)
    {
        $this->dni = $dni;

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
        $html = <<<EOF
        <div>
            <label for="precio">Precio por minuto:</label>
            <input type="number" id="precio" name="precio" min="0.0001" max="1.0000" step="0.0001" value="0.0015" required>
        </div>
        <div>
            <label for="dir">Dirección del parking:</label>
            <input type="text" id="dir" name="dir" required>
        </div>
        <div>
            <label for="ciudad">Ciudad:</label>
            <input type="text" id="ciudad" name="ciudad" required>
        </div>
        <div>
            <label for="cp">Código postal:</label>
            <input type="text" id="cp" name="cp" pattern="\d{5}" title="El código postal debe contener 5 dígitos" required>
        </div>
        <div>
            <label for="plazas">Número de plazas:</label>
            <input type="number" id="plazas" name="plazas" min="1" step="1" required>
        </div>
        <div>
            <label for="imgInput">Imagen (opcional):</label>
            <input type="file" id="imgInput" name="img" accept="image/*">
            <img class="imgPreview" alt="Vista previa de la imagen">
            <div class="imgError"></div>
        </div>
        <button type="submit">Crear</button>
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
        $precio = trim($datos['precio']  ?? '');
        $dir = trim($datos['dir']        ?? '');
        $ciudad = trim($datos['ciudad']  ?? '');
        $cp = trim($datos['cp']          ?? '');
        $plazas = trim($datos['plazas']  ?? '');

        $precio = filter_var($precio, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $dir = filter_var($dir, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $ciudad = filter_var($ciudad, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $cp = filter_var($cp, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $plazas = filter_var($plazas, FILTER_SANITIZE_NUMBER_INT);

        if(empty($precio) || empty($dir) || empty($ciudad) || empty($cp) || empty($plazas)){
            $result[] = 'Es necesario rellenar todos los campos requeridos';
            return $result;
        }

        if (!preg_match('/^\d{5}$/', $cp)) {
            $result[] = "El código postal es de 5 dígitos";
        }
        
        if ($plazas < 1) {
            $result[] = "El número de plazas debe ser al menos 1";
        }

        //Procesamiento de la imagen (campo opcional)
        $img = null;
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
                    $img = $targetFile;
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
        try
        {
            if (count($result) === 0) {
                if(empty(SAParking::registrarParking($this->dni,$dir,$precio,$ciudad,$cp,$plazas,$img))){
                    $result[] = "Error al insertar el parking a la base de datos";
                } else {
                    $result = "misParkings.php";
                }
            
            }
        }
        catch(parkingAlreadyExistsException $e)
        {
            $result[] = $e->getMessage();
        }

        return $result;
    }
}