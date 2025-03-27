<?php

namespace es\ucm\fdi\aw\ePark;

class ParkingDAO extends DAO {

    public static $instancia;
    public $mysqli; 

    public static function getSingleton() { //Patrón Singleton para única instancia de la clase
        if ( !self::$instancia instanceof self) { 
            self::$instancia = new self; 
        } 
        return self::$instancia; 
    }

    //Modificado
    protected function lastId(){

        $conn = Aplicacion::getInstance()->getConexionBd();

        $query="SELECT max(id) as num FROM Parking";

        $stmt = $conn->prepare($query);

        $stmt->execute();

        $stmt->bind_result($numero);

        if($stmt->fetch()){

            $stmt->close();

            return $numero;
        }

        return 0;
    }

    //Modificado
    
    public function insert(TOParking $p) {
        $dir = $p->getDir();
        $ciudad = $p->getCiudad();
        $cp = $p->getCP();
        $precio = $p->getPrecio();
        $nPlazas = $p->getNPlazas();
        $id = self::lastId() + 1; 
    
        $conn = Aplicacion::getInstance()->getConexionBd();
    
        $query = "INSERT INTO Parking (id, dir, ciudad, CP, precio, nPlazas) VALUES (?, ?, ?, ?, ?, ?)";
    
        $stmt = $conn->prepare($query);
    
        $stmt->bind_param("issddi", $id, $dir, $ciudad, $cp, $precio, $nPlazas);
    
        if ($stmt->execute()) {

            $stmt->close();

            return true; // éxito

        } else {

            $stmt->close();

            return false; // error
        }
    }
    

    //Modificado
    public function update(TOParking $p) {

        // Variables intermedias
        $dir = $p->getDir();
        $ciudad = $p->getCiudad();
        $cp = $p->getCP();
        $precio = $p->getPrecio();
        $nPlazas = $p->getNPlazas();
        $id = $p->getId();

        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = "UPDATE Parking SET dir = ?, ciudad = ?, CP = ?, precio = ?, nPlazas = ? WHERE id = ?";

        $stmt = $conn->prepare($query);

        $stmt->bind_param("ssddii", $dir, $ciudad, $cp, $precio, $nPlazas,$id);

        $resultado = $stmt->execute();

        $stmt->close();

        return $resultado;
    }


    //Modificado
    public function delete($id) {

        $query = "DELETE FROM Parking WHERE id = ?";

        $conn = Aplicacion::getInstance()->getConexionBd();

        $stmt = $conn->prepare($query);

        $id = (int) $id;

        $stmt->bind_param("i", $id);

        $resultado = $stmt->execute();

        $stmt->close();

        return $resultado;
    }

    //Modificado
    public function getById($id) {

        $parkings=[];

        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = "SELECT * FROM Parking WHERE id = ?";

        $stmt = $conn->prepare($query);

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $stmt->bind_result($id, $dir, $ciudad,$cp,$precio,$nPlazas);

        while ($stmt->fetch())
        {
            $parkings[] = new TOparking($id, $dir, $ciudad,$cp,$precio,$nPlazas);
        }

        $stmt->close();
        
        return $parkings; // Devuelve un array vacío si no hay parkings disponibles
    }

    
    //Modificado
    public function getAll() {

        $parkings=[];

        $conn = Aplicacion::getInstance()->getConexionBd();
        
        $query = "SELECT * FROM Parking";

        $stmt = $conn->prepare($query);

        $stmt->execute();

        $stmt->bind_result($id, $dir, $ciudad,$cp,$precio,$nPlazas);

        while ($stmt->fetch())
        {
            $parkings[] = new TOparking($id, $dir, $ciudad,$cp,$precio,$nPlazas);
        }

        $stmt->close();

        return $parkings;
        
    }



    //Modificado
   public function showAvailables() {
    $parkings = [];

    $conn = Aplicacion::getInstance()->getConexionBd();

    $query = "SELECT * FROM Parking WHERE nPlazas > 0";

    $result = $conn->query($query);

    if ($result === false) {
        die("Error en la consulta: " . $conn->error);
    }

    while ($row = $result->fetch_assoc()) {
        $parkings[] = new TOparking(
            $row['id'], 
            $row['dir'], 
            $row['ciudad'], 
            $row['cp'], 
            $row['precio'], 
            $row['nPlazas']
        );
    }

    return $parkings; // Devuelve un array vacío si no hay parkings disponibles
}

}

?>
