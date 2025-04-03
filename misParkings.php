<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/includes/config.php';

if(empty($_SESSION['login'])){
    $html = <<<EOF
    <p>Por favor inicia sesión para acceder a la funcionalidad</p>
    EOF;
}
else{
    if(isset($_SESSION['tipo'])){
        switch($_SESSION['tipo']){
            case '1': //Usuario normal
                $html = <<<EOF
                <p>Funcionalidad restringida a propietarios. El usuario no tiene permisos</p>
                EOF;
                break;
            case '2': //Propietario
                $form = new \es\ucm\fdi\aw\ePark\gestionProp($dni);
                $html = $form->Manage();
                break;
            case '3': //Admin
                $form = new \es\ucm\fdi\aw\ePark\gestionAdmin($dni);
                $html = $form->Manage();
                break;
            default: //Pruebas
                $form = new \es\ucm\fdi\aw\ePark\gestionAdmin($dni);
                $html = $form->Manage();
                break;
        }
    }
    else{
        $html = <<<EOF
        <p>Ha habido un error al procesar el tipo de usuario</p>
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