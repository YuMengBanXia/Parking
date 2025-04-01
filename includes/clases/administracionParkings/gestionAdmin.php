<?php

namespace es\ucm\fdi\aw\ePark;

class gestionAdmin extends administrarParking 
{

    protected function getParkings(){//función a sobreescribir
        return SAParking::mostrarParkings();
    }
}
?>