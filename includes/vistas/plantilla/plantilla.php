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
    <link rel="stylesheet" type="text/css" href="<?= RUTA_CSS ?>/estilo.css" />
    
</head>

<body>
    <!-- Mensajes de la petición anterior -->
    <?= $mensajes ?>

    <div id="container">

    <!-- Navegador superior -->
 
        <?php require RAIZ_APP . "/vistas/comun/cabecera.php"; ?>
   

    <!-- Contenido principal -->
    
        <main>
            <article>
                <?= $contenidoPrincipal ?>
            </article>
        </main>
    </div>

    </div>
    <!-- Pie de página -->
    <?php require RAIZ_APP . "/vistas/comun/pie.php"; ?>
</body>

</html>