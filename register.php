<?php

include __DIR__ . "/includes/clases/login/registerForm.php";

$tituloPagina = 'Registro en el sistema';

$form = new registerForm();

$htmlFormRegistro = $form->Manage();

$contenidoPrincipal = <<<EOS
<h1>Registro de usuario</h1>
$htmlFormRegistro
 <h1>Bienvenido a ePark, aplicación integrada de gestión de plazas de aparcamiento</h1>
        <p>Gestiona tus parkings desde casa fácilmente y/o encuentra sitio para aparcar en un solo click!</p>
        <img src="img/imagen_portada.jpeg" alt="Imagen de inicio">
        <a href="ParkingsLibres.php">
            <button>COGER TICKET</button>
        </a>
EOS;

require_once __DIR__ . "/includes/vistas/plantilla/plantilla.php";
?>