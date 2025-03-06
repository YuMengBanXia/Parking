<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

unset($_SESSION);

session_destroy(); 

$tituloPagina = 'Salir del sistema';

$contenidoPrincipal=<<<EOS
	<h1>Hasta pronto!</h1>
EOS;

require_once __DIR__ . "/includes/vistas/plantilla/plantilla.php";
?>