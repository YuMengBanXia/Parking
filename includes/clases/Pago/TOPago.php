<?php

namespace es\ucm\fdi\aw\ePark;

class TOPago {
    private int $id;

    private string $dni;

    private int $idParking;

    private float $importe;

    private string $fechaPago;
   
    public function __construct(int $id, string $dni, int $idParking, float $importe, string $fechaPago) {
        $this->id = $id;
        $this->dni = $dni;
        $this->idParking = $idParking;
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

    public function getIdParking(){
        return $this->idParking;
    }
}
?>
