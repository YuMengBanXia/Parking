<?php

require_once __DIR__ . '/includes/config.php';

$tituloPagina = 'Política de Cancelación';

$html = <<<EOF
<div class="policy-container">
    <section>
        <h4>1. Ámbito de aplicación</h4>
        <p>
        Esta Política de Cancelación se aplica a todas las reservas de plaza de aparcamiento
        realizadas a través de la plataforma ePark. Su aceptación es condición indispensable
        para la confirmación de cualquier reserva.
        </p>
    </section>

    <section>
        <h4>2. Plazos y porcentajes de reembolso</h4>
        <ol>
        <li><p>
            Cancelación con más de 24 horas de antelación<br>
            Plazo: ≥ 24 h antes de la hora de inicio de la reserva.<br>
            Reembolso: 100 % del importe abonado.
        </p></li>
        <li><p>
            Cancelación entre 24 h y 5 h de antelación<br>
            Plazo: ≥ 5 h y < 24 h antes de la hora de inicio.<br>
            Reembolso: 50 % del importe abonado.
        </p></li>
        <li><p>
            Cancelación con menos de 5 horas de antelación<br>
            Plazo: < 5 h antes de la hora de inicio.<br>
            Reembolso: 0 % (no procede devolución).
        </p></li>
        </ol>
    </section>

    <section>
        <h4>3. Procedimiento de cancelación</h4>
        <p>
        Todas las solicitudes deben gestionarse exclusivamente desde la sección “Mis Reservas” 
        de la plataforma ePark. La solicitud sólo se considerará válida si se presenta dentro de 
        los plazos arriba indicados.
        </p>
    </section>

    <section>
        <h4>4. Condiciones de reembolso</h4>
        <ul>
        <li><p>Los importes a devolver se abonarán por el mismo medio de pago empleado en la reserva.</p></li>
        <li><p>El plazo máximo para la ejecución del reembolso será de 7 días hábiles 
        desde la confirmación de la cancelación.</p></li>
        <li><p>ePark no asumirá costes adicionales por comisiones bancarias o de pasarela de pago.</p></li>
        </ul>
    </section>

    <section>
        <h4>5. Pago de la reserva</h4>
        <p>
        La reserva deberá estar totalmente abonada al menos 24 horas antes
        de la hora de inicio. En caso contrario, se procederá a cancelarla
        automáticamente sin derecho a reembolso.
        </p>
    </section>

    <section>
        <h4>6. Excepciones y casos especiales</h4>
        <p>
        ePark se reserva el derecho de denegar cualquier cancelación que no se realice por el canal
        oficial o que no cumpla los requisitos temporalmente establecidos. Cualquier duda o incidencia
        relativa a este proceso podrá ser atendida a través de nuestro servicio de atención al cliente.
        </p>
    </section>
</div>
EOF;

$contenidoPrincipal = <<<EOS
    <h3>Política de Cancelación de ePark</h3>
    $html;
EOS;

require_once __DIR__ . '/includes/vistas/plantilla/plantilla.php';

?>