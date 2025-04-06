<?php

require_once __DIR__ . '/includes/config.php';

if(empty($_SESSION["login"])){
    $html = <<<EOF
    <p>Por favor vuelve a iniciar sesión para acceder a la funcionalidad</p>
    EOF;
 }
 else{
    if(isset($_SESSION["tipo"])){
      if($_SESSION["tipo"] === 'propietario' || $_SESSION["tipo"] === 'administrador'){
         $parking = '';
         $dni = $_SESSION['dni'];
         if(isset($_GET['id'])){
            $id = $_GET['id'];
         }
         elseif(isset($_POST['id'])){
            $id = $_POST['id'];
         }

         if(empty(\es\ucm\fdi\aw\ePark\SAParking::comprobarDni($id,$dni)) && $_SESSION["tipo"] === 'propietario') {
            $html = <<<EOF
               <p>El parking seleccionado no pertenece a este usuario</p>
            EOF;
         }
         else{
            $parking = \es\ucm\fdi\aw\ePark\SAParking::obtenerParkingPorId($id);
            $form = new \es\ucm\fdi\aw\ePark\modificarForm($parking, $dni);
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