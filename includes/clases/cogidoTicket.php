<?php

namespace es\ucm\fdi\aw\ePark;

class cogidoTicket extends formBase{

    public function __construct()
    {
    
        parent::__construct('TicketCogido');
        
    }
    protected function CreateFields($datos){
        $ticket = $_GET['ticket'] ?? null;
        $matricula = $_GET['matricula'] ?? null;

        $html='';
        if($ticket!=null & $matricula!=null){
        $html =<<<EOF
            ID del ticket: $ticket <br>
            Matrícula: $matricula<br>
             <a href="index.php">
                <button type="button">Ir al inicio</button>
            </a>
        EOF;
        }
        return $html;
    }
    
    

    
}




