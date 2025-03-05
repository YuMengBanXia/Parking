<?php

session_start(); 

unset($_SESSION);

session_destroy(); 

$tituloPagina = 'Salir del sistema';

$contenidoPrincipal=<<<EOS
	<h1>Hasta pronto!</h1>
EOS;

require("includes/comun/plantilla.php");
?>