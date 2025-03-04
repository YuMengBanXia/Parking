<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="stylesheet" type="text/css" href="../comun/CSS/default.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?= $tituloPagina ?></title>
</head>

<body>

<div class="navbar">
<?php
   require __DIR__ . "/../comun/navbar.php";

?>
</div>

<div class="container">
<main>
	  	<article>
			<?= $contenidoPrincipal ?>
		</article>
</main>

</div>

</body>
</html>