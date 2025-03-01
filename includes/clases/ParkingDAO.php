<?php
require_once 'DAO.php';
require_once 'TOParking.php';

class ParkingDAO extends DAO {

    public static function getSingleton() { //Patrón Singleton para única instancia de la clase
        if ( !self::$instancia instanceof self) { 
            self::$instancia = new self; 
        } 

        return self::$instancia; 
    }
    
    public function insert(TOParking $p) {
        //asignar un ID libre al parking 
        $qr="SELECT id FROM parkings";
        $id=count(qr)+1;
        $query = "INSERT INTO parkings (id, dir, ciudad, CP, precio, n_plazas) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($query);
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->mysqli->error);
        }

        $stmt->bind_param("issddi", $id, $p->getDir(), $p->getCiudad(), $p->getCP(), $p->getPrecio(), $p->getNPlazas());
        return $stmt->execute();
    }

    public function update(TOParking $p) {
        $query = "UPDATE parkings SET dir = ?, ciudad = ?, CP = ?, precio = ?, n_plazas = ? WHERE id = ?";
        $stmt = $this->mysqli->prepare($query);
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->mysqli->error);
        }

        $stmt->bind_param("ssddii", $p->getDir(), $p->getCiudad(), $p->getCP(), $p->getPrecio(), $p->getNPlazas(), $p->getId());
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM parkings WHERE id = ?";
        $stmt = $this->mysqli->prepare($query);
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->mysqli->error);
        }

        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getById($id) {
        $query = "SELECT * FROM parkings WHERE id = $id";
        $result = $this->ejecutarConsulta($query);
        if (!empty($result)) {
            $row = $result[0];
            return new TOParking($row['id'], $row['dir'], $row['ciudad'], $row['CP'], $row['precio'], $row['n_plazas']);
        }
        return null;
    }

    public function getAll() {
        $query = "SELECT * FROM parkings";
        $result = $this->ejecutarConsulta($query);
        if($result){ //si no hay problemas en la ejecución de la consulta
            if(count($result)>0){ //si hay datos en la BBDD
                $parkings = [];
                foreach ($result as $row) {
                $parkings[] = new TOParking($row['id'], $row['dir'], $row['ciudad'], $row['CP'], $row['precio'], $row['n_plazas']);
                return $parkings;
            }

        }
        
        return null;
        
    }



    public function showAvailables() {
        $query = "SELECT * FROM parkings WHERE n_plazas>0";
        $result = $this->ejecutarConsulta($query);
        if($result){ //si no hay problemas en la ejecución de la consulta
            if(count($result)>0){ //si hay datos en la BBDD
                $parkings = [];
                foreach ($result as $row) {
                $parkings[] = new TOParking($row['id'], $row['dir'], $row['ciudad'], $row['CP'], $row['precio'], $row['n_plazas']);
                return $parkings;
            }

        }
        
        return null;
        
    }

}
?>
