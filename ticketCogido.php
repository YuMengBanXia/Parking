<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once __DIR__ . '/includes/config.php';

$tituloPagina = 'Ticket cogido';


$formTicketCogido=new \es\ucm\fdi\aw\ePark\cogidoTicket();


$htmlFormCogido=$formTicketCogido->Manage();


  
    $ticket = $_GET['ticket'] ?? null;
        $matricula = $_GET['matricula'] ?? null;
        $html='';
      

$contenidoPrincipal = <<<EOS
 <h3>DATOS DEL TICKET</h3>
        
        
            ID del ticket: $ticket <br>
            Matrícula: $matricula<br>
             <a href="index.php">
                <button type="button">Ir al inicio</button>
            </a>
        
        

EOS;




require_once __DIR__ .'/includes/vistas/plantilla/plantilla.php';

?>