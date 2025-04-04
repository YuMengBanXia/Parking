<?php

function mostrarSaludo()
{
    $rutaApp = RUTA_APP;
    if (isset($_SESSION['nombre']) && ($_SESSION["login"] === true)) {
        //Usuario registrado
        echo "Bienvenido {$_SESSION['nombre']} <a href='{$rutaApp}/logout.php'>Salir</a>";
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
        <a href="contacto.php">Contacto</a>
        <a href="miembros.php">Miembros</a>
        <a href="ticket.php">Coger Ticket</a>
        <a href="misParkings.php">Mis Parkings</a>
        <a href="crearParking.php">Crear Parking</a>
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