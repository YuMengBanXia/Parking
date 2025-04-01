<?php

namespace es\ucm\fdi\aw\ePark;

class gestionProp extends administrarParking
{

    protected function getParkings(){//función a sobreescribir
        return SAParking::mostrarParkings();
    }
}
?>