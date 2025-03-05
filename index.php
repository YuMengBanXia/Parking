<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);


$contenidoPrincipal = <<<EOS
     <h1>Bienvenido a ePark, aplicación integrada de gestión de plazas de aparcamiento</h1>
        <p>Gestiona tus parkings desde casa fácilmente y/o encuentra sitio para aparcar en un solo click!</p>
        <img src="img/imagen_portada.jpeg" alt="Imagen de inicio">
        <a href="ParkingsLibres.php">
            <button>COGER TICKET</button>
        </a>

EOS;

$tituloPagina='Portada';

require_once __DIR__ ."/includes/vistas/plantilla/plantilla.php";


?>

