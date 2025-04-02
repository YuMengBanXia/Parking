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

    
    //No hace falta insertar con el campo id porque este es auto_increment por lo que se asigna automáticamente
    public function insert(TOParking $p) {
        $dir = $p->getDir();
        $ciudad = $p->getCiudad();
        $cp = $p->getCP();
        $precio = $p->getPrecio();
        $nPlazas = $p->getNPlazas();

        $dni = $p->getDni();
        $img = $p->getImg() ?? '';
        
        try{
        $conn = Aplicacion::getInstance()->getConexionBd();
    
        $query = "INSERT INTO Parking (dni, dir, ciudad, CP, precio, nPlazas, img) VALUES (?, ?, ?, ?, ?, ?)";
    
        $stmt = $conn->prepare($query);
    
        $stmt->bind_param("isssddis", $id, $dni, $dir, $ciudad, $cp, $precio, $nPlazas, $img);
            
        $result=$stmt->execute();

        $stmt->close();

        }
        catch(\mysqli_sql_exception $e){
            if ($conn->sqlstate == 23000) 
            { 
                throw new parkingAlreadyExistsException("Ya existe el parking en la dirección '{$p->getDir()}' y ciudad {$p->getCiudad()}");
            }

            throw $e;
        }
        return $result;
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

        $img = $p->getImg() ?? '';
        
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = "UPDATE Parking SET dir = ?, ciudad = ?, CP = ?, precio = ?, nPlazas = ?, img = ? WHERE id = ?";

        $stmt = $conn->prepare($query);

        $stmt->bind_param("ssddisi", $dir, $ciudad, $cp, $precio, $nPlazas, $img, $id);

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

        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = "SELECT * FROM Parking WHERE id = ?";

        $stmt = $conn->prepare($query);

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $stmt->bind_result($id, $dni, $dir, $ciudad,$cp,$precio,$nPlazas, $img);

        if ($stmt->fetch())
        {
            $parking = new TOParking($id, $dni, $dir, $ciudad,$cp,$precio,$nPlazas, $img);
        }

        $stmt->close();
        
        return $parking; // Devuelve un array vacío si no hay parkings disponibles
    }

    
    //Modificado
    public function getAll() {

        $parkings = [];
    
        $conn = Aplicacion::getInstance()->getConexionBd();
    
        $query = "SELECT * FROM Parking";
    
        $result = $conn->query($query);
    
        if ($result === false) {
            die("Error en la consulta: " . $conn->error);
        }
    
        while ($row = $result->fetch_assoc()) {
            $parkings[] = new TOParking(
                $row['id'] ?? null,
                $row['dni'] ?? null,
                $row['precio'] ?? null,
                $row['dir'] ?? null,
                $row['ciudad'] ?? null,
                $row['cp'] ?? null,
                $row['nPlazas'] ?? null,
                $row['img'] ?? null
            );
        }
        
        $result->close();
        
        return $parkings; // Devuelve un array vacío si no hay parkings disponibles
        
    }


    /*Obsoleto
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
                $row['dni'],
                $row['precio'], 
                $row['dir'], 
                $row['ciudad'], 
                $row['cp'], 
                $row['nPlazas'],
                $row['img']
            );
        }
    
        return $parkings; // Devuelve un array vacío si no hay parkings disponibles
    }
    */
    
    public function getByDni($dni){
        $parkings=[];

        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = "SELECT * FROM Parking WHERE dni = ?";

        $stmt = $conn->prepare($query);

        $stmt->bind_param("s", $dni);

        $stmt->execute();

        $stmt->bind_result($id, $dni, $dir, $ciudad,$cp,$precio,$nPlazas, $img);

        while ($stmt->fetch())
        {
            $parkings[] = new TOParking($id, $dni, $dir, $ciudad,$cp,$precio,$nPlazas, $img);
        }

        $stmt->close();
        
        return $parkings;
    }

    public function getDni($id){
        $result = null;
        
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = "SELECT dni FROM Parking WHERE id = ?";

        $stmt = $conn->prepare($query);

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $stmt->bind_result($dni);

        if($stmt->fetch()){

            $result = $dni;
        }

        $stmt->close();

        return $result;
    }
}

?>
