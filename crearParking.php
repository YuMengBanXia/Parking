<?php
require_once __DIR__ . '/includes/config.php';

$app = Aplicacion::getInstance();
if($app->isCurrentUserLogged()){
   $tipo = $app->getAtributoPeticion('tipo');
   $dni = $app->getAtributoPeticion('dni');
   if(!empty($tipo)){
      if($tipo === 'propietario' || $tipo === 'administrador'){
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
else{
   $html = <<<EOF
   <p>Inicia sesión o regístrate como propietario para empezar a crear tus parkings</p>
   EOF;
}



$contenidoPrincipal = <<<EOS
   <h3>Crear Parking</h3>
   $html
   
EOS;



$tituloPagina='Crear Parking';


require_once __DIR__ .'/includes/vistas/plantilla/plantilla.php';

?>