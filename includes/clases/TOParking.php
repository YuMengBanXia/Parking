<?php
class TOParking {
    private $id;
    private $dir;
    private $ciudad;
    private $CP;
    private $precio;
    private $n_plazas;

    public function __construct($id, $dir, $ciudad, $CP, $precio, $n_plazas) {
        $this->id = $id;
        $this->dir = $dir;
        $this->ciudad = $ciudad;
        $this->CP = $CP;
        $this->precio = $precio;
        $this->n_plazas = $n_plazas;
    }

    public function getId() { return $this->id; }
    public function getDir() { return $this->dir; }
    public function getCiudad() { return $this->ciudad; }
    public function getCP() { return $this->CP; }
    public function getPrecio() { return $this->precio; }
    public function getNPlazas() { return $this->n_plazas; }

    public function setDir($dir) { $this->dir = $dir; }
    public function setCiudad($ciudad) { $this->ciudad = $ciudad; }
    public function setCP($CP) { $this->CP = $CP; }
    public function setPrecio($precio) { $this->precio = $precio; }
    public function setNPlazas($n_plazas) { $this->n_plazas = $n_plazas; }
}
?>
