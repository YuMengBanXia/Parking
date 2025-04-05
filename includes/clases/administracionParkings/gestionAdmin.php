<?php

namespace es\ucm\fdi\aw\ePark;

class gestionAdmin extends administrarParking 
{

    public function __construct($dni)
    {
        parent::__construct($dni);
    }

    protected function getParkings($dni){//función a sobreescribir
        return SAParking::mostrarParkings();
    }
}
?>