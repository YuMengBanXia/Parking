<?php

namespace es\ucm\fdi\aw\ePark;

class FormContacto extends FormBase
{
    public function __construct()
    {
        parent::__construct('Contacto',array('action' => 'mailto:correo@ejemplo.com') );
    }

    protected function createFields($datos)
    {
        // Usa comillas <<<EOF y cierra correctamente la sintaxis del heredoc con EOF;
        $html = <<<EOF
        
        <p>Complete el formulario para enviarnos su consulta.</p>

        
            <label for="nombre">Nombre:</label><br>
            <input type="text" id="nombre" name="nombre" required>
            <br><br>

            <label for="email">Correo Electrónico:</label><br>
            <input type="email" id="email" name="email" required>
            <br><br>

            <div class="radio-group">
                <label>Motivo de Contacto:</label><br>
                <label><input type="radio" name="motivo" value="Evaluación" required> Evaluación</label>
                <label><input type="radio" name="motivo" value="Sugerencias"> Sugerencias</label>
                <label><input type="radio" name="motivo" value="Críticas"> Críticas</label>
            </div>
            <br>

            <label for="mensaje">Mensaje:</label><br>
            <textarea id="mensaje" name="mensaje" rows="4" required></textarea>
            <br><br>

            <div class="checkbox-group">
                <input type="checkbox" id="terminos" name="terminos" required>
                <label for="terminos">He leído los términos y condiciones.</label>
            </div>
            <br>

            <button type="submit">Enviar</button>
        

        <br>
        <a href="index.php">
                <button type="button">Ir al inicio</button>
            </a>
        
        EOF;

        return $html;
    }

    protected function process($datos)
    {
        // Aquí podrías procesar los datos recibidos
        // Por ahora simplemente devuelve un mensaje de éxito
        return 'Gracias por contactarnos. Te responderemos pronto.';
    }
}
