<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="es">


<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../comun/CSS/default.css">
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