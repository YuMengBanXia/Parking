<?php
session_start();

function mostrarSaludo($nombre)
{
        if (isset($_SESSION['login'])) { //Usuario registrado
                echo "Bienvenido " . $nombre . " <a href='../../../logout.php'>(salir)</a>";
        } else { //Usuario no registrado
                echo "Usuario desconocido. <a href='../../../login.php'>Login</a>";
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
        </div>

        <div class="saludo">
                <?php mostrarSaludo($_SESSION['nombre']); ?>
        </div>
</header>


</html>