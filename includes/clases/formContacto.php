<?php

namespace es\ucm\fdi\aw\ePark;

class FormContacto extends FormBase
{
    public function __construct()
    {
        parent::__construct('Contacto');
    }

    protected function createFields($datos)
    {
        // Usa comillas <<<EOF y cierra correctamente la sintaxis del heredoc con EOF;
        $html = <<<EOF
        
        <h1> Formulario de contacto</h1>
        <fieldset>
        <p>Complete el formulario para enviarnos su consulta.</p>
           
        
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required><br>
            <br>
            <label for="email">Correo Electrónico:</label>
            <input type="text" id="email" name="email" required>
            
          

            <div class="radio-group">
                <p>Motivo de Contacto:</p>
                <label><input type="radio" name="motivo" value="Evaluación" required> Evaluación</label>
                <label><input type="radio" name="motivo" value="Sugerencias"> Sugerencias</label>
                <label><input type="radio" name="motivo" value="Críticas"> Críticas</label>
                

            </div>


            <br>
            <p>Mensaje:</p>
            <textarea id="mensaje" name="mensaje" rows="4" required></textarea>
            <br><br>

   

            <div class="checkbox-group">
                <input type="checkbox" id="terminos" name="terminos" required>
                <label for="terminos">He leído los términos y condiciones.</label>
            </div>
            <br>

            <button type="submit">Enviar</button>
        
        <a href="index.php" class="btn-link">Ir al inicio</a>

        
        </fieldset>
        EOF;

        return $html;
    }

    protected function process($datos)
    {
        // Aquí podrías procesar los datos recibidos
        // Por ahora simplemente devuelve un mensaje de éxito
        return $result='index.php';
    }
}
