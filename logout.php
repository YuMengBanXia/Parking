<?php

require_once __DIR__ . '/includes/config.php';

// Iniciar la sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Limpiar todas las variables de sesión
session_unset();

// Destruir la sesión
session_destroy();

$tituloPagina = 'Salir del sistema';

$contenidoPrincipal = <<<EOS
    <h1>¡Hasta pronto!</h1>
    <p>Has cerrado sesión correctamente. Esperamos verte de nuevo pronto.</p>
EOS;

require_once __DIR__ . "/includes/vistas/plantilla/plantilla.php";
?>