<?php

require_once __DIR__ . '/includes/config.php';

$contenidoPrincipal = <<<EOS
     <div class="form">
        <h1>Contacto</h1>
        <p>Complete el formulario para enviarnos su consulta.</p>

        <form action="mailto:correo@ejemplo.com" method="post" enctype="text/plain">
            <label for="nombre">Nombre:</label><br>
            <input type="text" id="nombre" name="nombre" required>
            <br><br>
            <label for="email">Correo Electrónico:</label> <br>
            <input type="email" id="email" name="email" required>
            <div class="radio-group"> <br>
                <label>Motivo de Contacto:</label>
                <label><input type="radio" name="motivo" value="Evaluación" required> Evaluación</label>
                <label><input type="radio" name="motivo" value="Sugerencias"> Sugerencias</label>
                <label><input type="radio" name="motivo" value="Críticas"> Críticas</label>
            </div>
            <br>
            <label for="mensaje">Mensaje:</label>
            <textarea id="mensaje" name="mensaje" rows="4" required></textarea>

            <div class="checkbox-group">
                <input type="checkbox" name="terminos" required>
                <label>He leído los términos y condiciones.</label>
            </div>

            <button type="submit">Enviar</button>
        </form>

        <a href="index.php" class="back-link">Volver al inicio</a>
    </div>


EOS;

$tituloPagina='Contacto';

require_once __DIR__ ."/includes/vistas/plantilla/plantilla.php";


?>

