<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

function mostrarSaludo()
{
        if (isset($_SESSION['nombre'])) { //Usuario registrado
                echo "Bienvenido " . $_SESSION['nombre'] . " .<a href='logout.php'>Salir</a>";
        } else { //Usuario no registrado
                echo "Usuario desconocido. <a href='login.php'>Login.</a> <a href='register.php'>Registro</a>";
        }
}
?>

<!DOCTYPE html>
<html lang="es">

<header>
        <div class="navbar">
                <a href="index.php">Inicio</a>
                <a href="detalles.php">Detalles</a>
                <a href="contacto.php">Contacto</a>
                <a href="miembros.php">Miembros</a>
                <a href="ParkingsLibres.php">Coger Ticket</a>
        </div>

        <div class="saludo">
                <?php mostrarSaludo(); ?>
        </div>
</header>


</html>