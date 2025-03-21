<?php

require_once __DIR__.'/TOParking.php';
require_once __DIR__.'../DAO.php';

class ParkingDAO extends DAO {

    public static $instancia;
    public $mysqli; 

    public static function getSingleton() { //Patrón Singleton para única instancia de la clase
        if ( !self::$instancia instanceof self) { 
            self::$instancia = new self; 
        } 
        return self::$instancia; 
    }

    public function lastId(){
        $query="SELECT max(id) as num FROM parkings";
        $result = $this->ejecutarConsulta($query);
        if(empty($result)){
            return 1;
        }
        return $result['num'];
    }
    
    public function insert(TOParking $p) {
        $dir = $p->getDir();
        $ciudad = $p->getCiudad();
        $cp = $p->getCP();
        $precio = $p->getPrecio();
        $n_plazas = $p->getNPlazas();
        
        $query = "INSERT INTO parkings (dir, ciudad, CP, precio, n_plazas) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($query);
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->mysqli->error);
        }

        $stmt->bind_param("ssddi", $dir, $ciudad, $cp, $precio, $n_plazas);
        return $stmt->execute();
    }

    public function update(TOParking $p) {
        $query = "UPDATE parkings SET dir = ?, ciudad = ?, CP = ?, precio = ?, n_plazas = ? WHERE id = ?";
        $stmt = $this->mysqli->prepare($query);
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->mysqli->error);
        }
    
        // Variables intermedias
        $dir = $p->getDir();
        $ciudad = $p->getCiudad();
        $cp = $p->getCP();
        $precio = $p->getPrecio();
        $n_plazas = $p->getNPlazas();
        $id = $p->getId();
    
        //Pasar solo variables por referencia
        $stmt->bind_param("ssddii", $dir, $ciudad, $cp, $precio, $n_plazas, $id);
    
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM parkings WHERE id = ?";
        $stmt = $this->mysqli->prepare($query);
    
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->mysqli->error);
        }
        //Convertir ID a entero para evitar errores
        $id = (int) $id;
        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();
    
        if ($resultado) {
            return $stmt->affected_rows > 0; //Retorna true si se eliminó correctamente
        } else {
            return false; //Si no se eliminó nada, retorna false
        }
    }
    public function getById($id) {
        $query = "SELECT * FROM parkings WHERE id = $id";
        $result = $this->ejecutarConsulta($query);
        if (!empty($result)) {
            $row = $result[0];
            return new TOParking($row['id'], $row['dir'], $row['ciudad'], $row['CP'], $row['precio'], $row['n_plazas']);
        }
        return 0;//Ids validos empiezan a contarse desde el 1 incluido
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
        }
        return [];
    }
    public function showAvailables() {
        $query = "SELECT * FROM parkings WHERE n_plazas > 0";
        $result = $this->ejecutarConsulta($query); // Ya devuelve un array de datos
    
        if (!empty($result)) { // Verifica si hay datos en la BBDD
            $parkings = [];
            foreach ($result as $row) {
                $parkings[] = new TOParking($row['id'], $row['dir'], $row['ciudad'], $row['CP'], $row['precio'], $row['n_plazas']);
            }
            
            return $parkings; // Devuelve todos los objetos TOParking
        }
        
        return []; // Devuelve un array vacío si no hay parkings disponibles
    }
}

?>
