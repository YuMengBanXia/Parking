<?php
require_once __DIR__ . '/plantilla_utils.php';
$mensajes = mensajesPeticionAnterior();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?= $tituloPagina ?></title>
    <link rel="stylesheet" type="text/css" href="<?= RUTA_CSS ?>/default.css" />
</head>

<body>
    <!-- Mensajes de la petición anterior -->
    <?= $mensajes ?>

    <!-- Navegador superior -->
    <div id="navbar">
        <?php require RAIZ_APP . "/vistas/comun/navbar.php"; ?>
    </div>

    <!-- Contenido principal -->
    <div class="container">
        <main>
            <article>
                <?= $contenidoPrincipal ?>
            </article>
        </main>
    </div>

    <!-- Pie de página -->
    <?php require RAIZ_APP . "/vistas/comun/pie.php"; ?>
</body>

</html>