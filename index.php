<?php

require_once __DIR__ . '/includes/config.php';

$tituloPagina = 'Portada';

$contenidoPrincipal = <<<EOS
<h1>Bienvenido a ePark, aplicación integrada de gestión de plazas de aparcamiento</h1>
<p>Gestiona tus parkings desde casa fácilmente y/o encuentra sitio para aparcar en un solo click!</p>
<img src="img/imagen_portada.jpeg" alt="Imagen de inicio">
EOS;

require_once RAIZ_APP . '/vistas/plantilla/plantilla.php';
