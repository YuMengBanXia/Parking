<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/includes/config.php';

if(empty($_SESSION["login"])){
    $html = <<<EOF
    <p>Por favor vuelve a iniciar sesión para acceder a la funcionalidad</p>
    EOF;
 }
 else{
    if(isset($_SESSION["tipo"])){
       if($_SESSION["tipo"] === 2 || $_SESSION["tipo"] === 3){
         $id = $_GET['id'];
         $parking = \es\ucm\fdi\aw\ePark\SAParking::obtenerParkingPorId($id);
         if(empty($parking)){
            $html = <<<EOF
            <p>No existe ningún parking con el id seleccionado</p>
            EOF;
         }
         else{
            $form = new \es\ucm\fdi\aw\ePark\modificarForm($parking);
            $html = $form->Manage();
         }
       }
       else{
          $html = <<<EOF
          <p>El usuario no tiene acceso a esta funcionalidad</p>
          EOF;
       }
    } else {
       $html = <<<EOF
         <p>Ha habido un error al procesar el tipo de usuario</p>
       EOF;
    }
 }



$contenidoPrincipal = <<<EOS
   <h3>Modificar parking</h3>
   $html
   
EOS;



$tituloPagina='Mis Parkings';


require_once __DIR__ .'/includes/vistas/plantilla/plantilla.php';

?>