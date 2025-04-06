<?php
require_once __DIR__ . '/includes/config.php';

if(empty($_SESSION['login'])){
    $html = <<<EOF
    <p>Por favor inicia sesión para acceder a la funcionalidad</p>
    EOF;
}
else{
    if(isset($_SESSION['tipo'])){
        switch($_SESSION['tipo']){
            case 'cliente': //Usuario normal
                $html = <<<EOF
                <p>Funcionalidad restringida. El usuario no tiene permisos</p>
                EOF;
                break;
            case 'propietario': //Propietario
                $dni = $_SESSION['dni'];
                $form = new \es\ucm\fdi\aw\ePark\gestionProp($dni);
                $html = $form->Manage();
                break;
            case 'administrador': //Admin
                $dni = $_SESSION['dni'];
                $form = new \es\ucm\fdi\aw\ePark\gestionAdmin($dni);
                $html = $form->Manage();
                break;
            default: //Error
                $html = <<<EOF
                <p>Ha habido un error al procesar el tipo de usuario</p>
                EOF;
                break;
        }
    }
    else{
        $html = <<<EOF
        <p>Tipo de usuario no especificado</p>
        EOF;
    }
}



$contenidoPrincipal = <<<EOS
   <h3>Gestionar parkings</h3>
   $html
   
EOS;



$tituloPagina='Mis Parkings';


require_once __DIR__ .'/includes/vistas/plantilla/plantilla.php';

?>