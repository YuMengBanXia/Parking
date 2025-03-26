<?php

require_once __DIR__ . '/includes/config.php';

$tituloPagina = 'Registro en el sistema';

$form = new \es\ucm\fdi\aw\ePark\registerForm();

$htmlFormRegistro = $form->Manage();

$contenidoPrincipal = <<<EOS
<h1>Formulario de registro</h1>
$htmlFormRegistro
EOS;

require_once __DIR__ . "/includes/vistas/plantilla/plantilla.php";
?>