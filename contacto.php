<?php

require_once __DIR__ . '/includes/config.php';
$form = new \es\ucm\fdi\aw\ePark\FormContacto();

$htmlFormContacto = $form->Manage();

$contenidoPrincipal = <<<EOS

$htmlFormContacto

EOS;

$tituloPagina='Contacto';

require_once __DIR__ ."/includes/vistas/plantilla/plantilla.php";


?>

