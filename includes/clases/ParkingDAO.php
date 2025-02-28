<?php
require_once 'PDatabase.php';
require_once 'TOParking.php';

class ParkingDAO extends DAO{
    private $db;

    public function __construct() {
        parent::__construct();
    }

    public function insert(TOParking $p) {
        $query = "INSERT INTO parkings (dir, ciudad, CP, precio, n_plazas) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->ejecutarConsulta($query);
        $stmt->bind_param("ssddi", $p->getDir(), $p->getCiudad(), $p->getCP(), $p->getPrecio(), $p->getNPlazas());
        return $stmt->execute();
    }
    
    public function update(TOParking $p) {
        $query = "UPDATE parkings SET dir = ?, ciudad = ?, CP = ?, precio = ?, n_plazas = ? WHERE id = ?";
        $stmt = $this->ejecutarConsulta($query);
        $stmt->bind_param("ssddii", $p->getDir(), $p->getCiudad(), $p->getCP(), $p->getPrecio(), $p->getNPlazas(), $p->getId());
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM parkings WHERE id = ?";
        $stmt = $this->ejecutarConsulta($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getById($id) {
        $query = "SELECT * FROM parkings WHERE id = ?";
        $stmt = $this->ejecutarConsulta($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new TOParking($row['id'], $row['dir'], $row['ciudad'], $row['CP'], $row['precio'], $row['n_plazas']);
        }
        return null;
    }

    public function getAll() {
        $query = "SELECT * FROM parkings";
        $result = $this->db->query($query);
        $parkings = [];//array para almacenar parkings
        while ($row = $result->fetch_assoc()) {//cuando extrae una fila y no este vacia 
            $parkings[] = new TOParking($row['id'], $row['dir'], $row['ciudad'], $row['CP'], $row['precio'], $row['n_plazas']);
        }
        return $parkings;//devuelve el array
    }
}
?>
