<?php

namespace es\ucm\fdi\aw\ePark;

class TOReserva{
    private $codigo;
    private $dni;
    private $id;
    private $fecha_ini;
    private $fecha_fin;
    private $matricula;
    private $importe;
    private $estado;
    private $num_orden;

    public function __construct($codigo, $dni, $id, $fecha_ini, $fecha_fin, $matricula, $importe, $estado = null, $num_orden = null){
        $this->codigo = $codigo;
        $this->dni = $dni;
        $this->id = $id;
        $this->fecha_ini = $fecha_ini;
        $this->fecha_fin = $fecha_fin;
        $this->matricula = $matricula;
        $this->importe = $importe;
        $this->estado = $estado ?? 'PENDIENTE';
        $this->num_orden = $num_orden;
    }

    public function get_codigo(){return $this->codigo;}
    public function get_dni(){return $this->dni;}
    public function get_id(){return $this->id;}
    public function get_fecha_ini(){return $this->fecha_ini;}
    public function get_fecha_fin(){return $this->fecha_fin;}
    public function get_matricula(){return $this->matricula;}
    public function get_estado(){return $this->estado;}
    public function get_importe() {return $this->importe;}
    public function get_num_orden(){return $this->num_orden;}
}
?>