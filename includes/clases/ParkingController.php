<?php
require_once 'ParkingDAO.php';
require_once 'TOParking.php';

class ParkingController {
    private $dao;

    public function __construct() {
        $this->dao = new ParkingDAO();
    }

    public function registrarParking($dir, $ciudad, $CP, $precio, $n_plazas) {
        $parking = new TOParking(null, $dir, $ciudad, $CP, $precio, $n_plazas);
        return $this->dao->insert($parking);
    }

    public function modificarParking($id, $dir, $ciudad, $CP, $precio, $n_plazas) {
        $parking = new TOParking($id, $dir, $ciudad, $CP, $precio, $n_plazas);
        return $this->dao->update($parking);
    }

    public function eliminarParking($id) {
        return $this->dao->delete($id);
    }

    public function listarParkings() {
        return $this->dao->getAll();
    }

    public function obtenerParking($id) {
        return $this->dao->getById($id);
    }
}
?>
