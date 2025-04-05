<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/includes/config.php';

if(empty($_SESSION["login"])){
   $html = <<<EOF
   <p>Inicia sesión o regístrate como propietario para empezar a crear tus parkings</p>
   EOF;
}
else{
   if(isset($_SESSION["tipo"])){
      if($_SESSION["tipo"] === 'propietario' || $_SESSION["tipo"] === 'administrador'){
         $dni= $_SESSION['dni'];
         $form = new \es\ucm\fdi\aw\ePark\nuevoParking($dni);
         $html = $form->Manage();
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
   <h3>Crear Parking</h3>
   $html
   
EOS;



$tituloPagina='Crear Parking';


require_once __DIR__ .'/includes/vistas/plantilla/plantilla.php';

?>