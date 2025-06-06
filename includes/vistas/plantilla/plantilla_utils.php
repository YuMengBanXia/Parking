<?php

use es\ucm\fdi\aw\ePark\Aplicacion;

function mensajesPeticionAnterior()
{
    $app = Aplicacion::getInstance();
    $mensajes = $app->getAtributoPeticion('mensajes');
    $html = '';
    if ($mensajes) {
        $html = '<div class="mensajes">';
        $contador = 0;
        foreach($mensajes as $mensaje) {
            $contador++;
            $idMensaje = "mensaje{$contador}"; 
            $html .= <<<EOS
            <input id="$idMensaje" type="checkbox">
            <div class="mensaje">
                <div class="cabecera"><label for="$idMensaje">×</label></div>
                <div class="contenido">$mensaje</div>
            </div>
            EOS;
        }
        $html .= '</div>';
    }

    return $html;
}