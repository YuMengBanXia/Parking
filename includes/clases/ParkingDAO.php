<?php
 
class ParkingDAO {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function insert(TOParking $p) {
        $query = "INSERT INTO parkings (nombre, direccion, capacidad) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssi", $p->getNombre(), $p->getDireccion(), $p->getCapacidad());
        return $stmt->execute();
    }

    public function update(TOParking $p) {
        $query = "UPDATE parkings SET nombre = ?, direccion = ?, capacidad = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssii", $p->getNombre(), $p->getDireccion(), $p->getCapacidad(), $p->getId());
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM parkings WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    public function getById($id) {
        $query = "SELECT * FROM parkings WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new TOParking($row['id'], $row['nombre'], $row['direccion'], $row['capacidad']);
        }
        return null;
    }

    public function getAll() {
        $query = "SELECT * FROM parkings";
        $result = $this->db->query($query);
        $parkings = [];//crea un array para almacenar los resultados
        while ($row = $result->fetch_assoc()) {//obtiene cada fila de la consulta un array 
            $parkings[] = new TOParking($row['id'], $row['nombre'], $row['direccion'], $row['capacidad']);
        }
        return $parkings;//devuelve la lista
    }
}
?>
