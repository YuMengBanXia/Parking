<?php
class TOTicket {
    private $codigo;
    private $id;
    private $matricula;
    private $fecha;

    public function __construct($codigo, $id, $matricula = null, $fecha = null) {
        $this->codigo = $codigo; 
        $this->id = $id;
        $this->matricula = $matricula;
        $this->fecha = empty($fecha)?new DateTime():$fecha;
    }

    public function get_codigo() {return $this->codigo;}
    public function get_id() {return $this->id;}
    public function get_matricula() {return $this->matricula;}
    public function get_fecha() {return $this->fecha;}
}
?>