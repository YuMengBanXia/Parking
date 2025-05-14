<?php

function mostrarSaludo()
{
    $rutaApp = RUTA_APP;
    if (isset($_SESSION['nombre']) && ($_SESSION["login"] === true)) {
        //Usuario registrado
        echo "Bienvenido {$_SESSION['nombre']} <a href='{$rutaApp}/logout.php'>Salir</a>";
        if (
            isset($_SESSION['tipo'])
            && in_array($_SESSION['tipo'], ['administrador', 'propietario'], true)
        ) {
            echo " <a href='{$rutaApp}/informes_pagos.php' class='btn'>Resumen del pago</a>";
        }
        //echo "Bienvenido " . $_SESSION['nombre'] . " .<a href='{$rutaApp}/logout.php'>Salir</a>";
    } else { //Usuario no registrado
        echo "Usuario desconocido <a href='{$rutaApp}/login.php'>Login</a> <a href='{$rutaApp}/register.php'>Registro</a>";
    }
}
?>

<header>
     <!-- Navegador superior -->
     <div class="navbar">
        <a href="index.php">Inicio</a>
        <a href="detalles.php">Detalles</a>
        <a href="miembros.php">Miembros</a>
        <a href="ticket.php">Coger Ticket</a>
        <a href="misParkings.php">Mis Parkings</a>
        <a href="crearParking.php">Crear Parking</a>
        <a href="pagoVista.php">Salida Parking</a>
        <a href="crearReserva.php">Crear Reserva</a>
        <a href="misReservas.php">Mis Reservas</a>
    </div>

<div class="barra-superior">
    <img src="img/logo.png" alt="Logo" class="logo">
   
    <!-- Saludo al usuario-->
    <div class="saludo">
        <?php mostrarSaludo(); ?>
    </div>
    </div>
</header>

</html>