<?php
require_once __DIR__ . '/plantilla_utils.php';
$mensajes = mensajesPeticionAnterior();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?= $tituloPagina ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="<?= RUTA_CSS ?>/default.css" />
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
</head>

<body>
    <!-- Mensajes de la petición anterior -->
    <?= $mensajes ?>

    <!-- Navegador superior -->
    <div id="navbar">
        <?php require RAIZ_APP . "/vistas/comun/cabecera.php"; ?>
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