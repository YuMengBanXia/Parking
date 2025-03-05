<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="CSS/default.css">
    <title><?= $tituloPagina ?></title>
</head>

<body>

    <div id="navbar">
        <?php require __DIR__ . "/../comun/navbar.php"; ?>
    </div>

    <div class="container">
        <main>
            <article>
                <?= $contenidoPrincipal ?>
            </article>
        </main>
    </div>

    <?php require __DIR__ . "/../comun/pie.php"; ?>

</body>

</html>