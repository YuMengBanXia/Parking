<?php

namespace es\ucm\fdi\aw\ePark;

class gestionProp extends administrarParking
{

    public function __construct($dni)
    {
        parent::__construct($dni);
    }

    protected function getParkings($dni){//función a sobreescribir
        return SAParking::getByDni($dni);
    }
}
?>