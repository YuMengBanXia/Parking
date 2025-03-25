<?php

include __DIR__ ."/../vistas/comun/formBase.php";

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
            Se ha cogido ticket con id: $ticket y matrícula: $matricula exitosamente
        EOF;
        }
        return $html;
    }
    
    

    
}




