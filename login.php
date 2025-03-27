<?php

require_once __DIR__ . '/includes/config.php';

$form = new \es\ucm\fdi\aw\ePark\loginForm();
$htmlFormRegistro = $form->Manage();

$tituloPagina = 'Iniciar Sesión';

$contenidoPrincipal = <<<EOS
<h1>Formulario de incio de sesión</h1>
$htmlFormRegistro
EOS;

require_once __DIR__ . "/includes/vistas/plantilla/plantilla.php";
