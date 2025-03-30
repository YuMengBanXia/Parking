<?php

namespace es\ucm\fdi\aw\ePark;

class TOParking {
    private $id;
    private $dni;
    private $dir;
    private $ciudad;
    private $CP;
    private $precio;
    private $n_plazas;
    private $img;

    public function __construct($id, $dni, $precio, $dir, $ciudad, $CP, $n_plazas, $img=null) {
        $this->id = $id; 
        $this->dni = $dni;
        $this->dir = $dir;
        $this->ciudad = $ciudad;
        $this->CP = $CP;
        $this->precio = $precio;
        $this->n_plazas = $n_plazas;
        $this->img = $img; 
    }

    public function getId() { return $this->id; }
    public function getDni() { return $this->dni; }
    public function getDir() { return $this->dir; }
    public function getCiudad() { return $this->ciudad; }
    public function getCP() { return $this->CP; }
    public function getPrecio() { return $this->precio; }
    public function getNPlazas() { return $this->n_plazas; }
    public function getImg() { return $this->img; }

    public function setId($ID){$this->id=$ID;}
    public function setDni($dni){$this->dni=$dni;}
    public function setDir($dir) { $this->dir = $dir; }
    public function setCiudad($ciudad) { $this->ciudad = $ciudad; }
    public function setCP($CP) { $this->CP = $CP; }
    public function setPrecio($precio) { $this->precio = $precio; }
    public function setNPlazas($n_plazas) { $this->n_plazas = $n_plazas; }
    public function setImg($img) { $this->img = $img; }
}
?>
