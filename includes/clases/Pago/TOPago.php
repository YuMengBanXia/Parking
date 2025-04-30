<?php

namespace es\ucm\fdi\aw\ePark;

class TOPago {
    private int $id;

    private string $dni;

    private float $importe;

    private string $fechaPago;

    public function __construct(int $id, string $dni, float $importe, string $fechaPago) {
        $this->id = $id;
        $this->dni = $dni;
        $this->importe = $importe;
        $this->fechaPago = $fechaPago;

    }


   public function getId(){
        return $this->id;
    }

   public function getDni() {
    return $this->dni;
    }

    public function getImporte(){
        return $this->importe;
    }

    public function getFechaPago(){
        return $this->fechaPago;
    }
}
?>
