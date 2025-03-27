<?php

require_once __DIR__ . '/includes/config.php';

$form = new \es\ucm\fdi\aw\ePark\registerForm();
$htmlFormRegistro = $form->Manage();

$tituloPagina = 'Registrar usuario';

$contenidoPrincipal = <<<EOS
<h1>Formulario de registro de usuario</h1>
$htmlFormRegistro
EOS;

require_once __DIR__ . "/includes/vistas/plantilla/plantilla.php";
