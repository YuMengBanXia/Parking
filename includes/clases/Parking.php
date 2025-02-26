<?php
class TOParking {
    private $id;
    private $nombre;
    private $direccion;
    private $capacidad;

    public function __construct($id, $nombre, $direccion, $capacidad) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->direccion = $direccion;
        $this->capacidad = $capacidad;
    }

    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getDireccion() { return $this->direccion; }
    public function getCapacidad() { return $this->capacidad; }

    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setDireccion($direccion) { $this->direccion = $direccion; }
    public function setCapacidad($capacidad) { $this->capacidad = $capacidad; }
}
?>

