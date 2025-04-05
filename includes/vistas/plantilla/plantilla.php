<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?= $tituloPagina ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" media="screen and (min-width: 700px)" type="text/css" href="<?= RUTA_CSS ?>/estiloPC.css" />
    <link rel="stylesheet" media="screen and (max-width: 699px)" type="text/css" href="<?= RUTA_CSS ?>/estiloMovil.css" />
    <link rel="stylesheet" type="text/css" href="<?= RUTA_CSS ?>/estilo.css" />
</head>

<body>

    <div id="container">

        <!-- Navegador superior -->
        <?php require RAIZ_APP . "/vistas/comun/cabecera.php"; ?>

        <!-- Contenido principal -->
        <main>
            <article>
                <?= $contenidoPrincipal ?>
            </article>
        </main>

        <!-- Pie de página -->
        <?php require RAIZ_APP . "/vistas/comun/pie.php"; ?>

    </div>
</body>

</html>