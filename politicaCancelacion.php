<?php

require_once __DIR__ . '/includes/config.php';

$tituloPagina = 'Política de Cancelación';

$html = <<<EOF
    <section>
        <h2>1. Ámbito de aplicación</h2>
        <p>
        Esta Política de Cancelación se aplica a todas las reservas de plaza de aparcamiento
        realizadas a través de la plataforma ePark. Su aceptación es condición indispensable
        para la confirmación de cualquier reserva.
        </p>
    </section>

    <section>
        <h2>2. Plazos y porcentajes de reembolso</h2>
        <ol>
        <li>
            Cancelación con más de 24 horas de antelación<br>
            Plazo: ≥ 24 h antes de la hora de inicio de la reserva.<br>
            Reembolso: 100 % del importe abonado.
        </li>
        <li>
            Cancelación entre 24 h y 5 h de antelación<br>
            Plazo: ≥ 5 h y < 24 h antes de la hora de inicio.<br>
            Reembolso: 50 % del importe abonado.
        </li>
        <li>
            Cancelación con menos de 5 horas de antelación<br>
            Plazo: < 5 h antes de la hora de inicio.<br>
            Reembolso: 0 % (no procede devolución).
        </li>
        </ol>
    </section>

    <section>
        <h2>3. Procedimiento de cancelación</h2>
        <p>
        Todas las solicitudes deben gestionarse exclusivamente desde la sección “Mis Reservas” 
        de la plataforma ePark. La solicitud sólo se considerará válida si se presenta dentro de 
        los plazos arriba indicados.
        </p>
    </section>

    <section>
        <h2>4. Condiciones de reembolso</h2>
        <ul>
        <li>Los importes a devolver se abonarán por el mismo medio de pago empleado en la reserva.</li>
        <li>El plazo máximo para la ejecución del reembolso será de 7 días hábiles 
        desde la confirmación de la cancelación.</li>
        <li>ePark no asumirá costes adicionales por comisiones bancarias o de pasarela de pago.</li>
        </ul>
    </section>

    <section>
        <h2>5. Excepciones y casos especiales</h2>
        <p>
        ePark se reserva el derecho de denegar cualquier cancelación que no se realice por el canal
        oficial o que no cumpla los requisitos temporalmente establecidos. Cualquier duda o incidencia
        relativa a este proceso podrá ser atendida a través de nuestro servicio de atención al cliente.
        </p>
    </section>
EOF;

$contenidoPrincipal = <<<EOS
    <h3>Política de Cancelación de ePark</h3>
    $html;
EOS;

require_once __DIR__ . '/includes/vistas/plantilla/plantilla.php';

?>