<?php
session_start();

include("includes/clases/login/loginForm.php");

$tituloPagina = 'Portada';

$form = new loginForm(); 

$htmlFormLogin = $form->Manage();

$contenidoPrincipal = <<< EOS
<h1>Login de usuario</h1>
$htmlFormLogin
EOS;

require("includes/vistas/comun/plantilla/plantilla.php");
?>