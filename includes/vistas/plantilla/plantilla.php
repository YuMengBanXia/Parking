<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="stylesheet" type="text/css" href="../comun/CSS/default.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?= $tituloPagina ?></title>
</head>

<body>

<div id="contenedor">
<?php
   require __DIR__ . "/../comun/navbar.php";

?>

<main>
	  	<article>
			<?= $contenidoPrincipal ?>
		</article>
</main>

</div>

</body>
</html>