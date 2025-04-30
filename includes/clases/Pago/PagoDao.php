<?php

namespace es\ucm\fdi\aw\ePark;

class PagoDao extends DAO {

    public static $instancia;
    
    private function __construct(){}

    public static function getSingleton() { //Patrón Singleton para única instancia de la clase

        if ( !self::$instancia instanceof self) { 
            self::$instancia = new self; 
        } 
        return self::$instancia; 
    }
    

    //No hace falta insertar con el campo id porque este es auto_increment por lo que se asigna automáticamente
    public function insert(TOPago $p): bool
    {
        $dni       = $p->getDni();
        $importe   = $p->getImporte();
        $fechaPago = $p->getFechaPago();

        $conn = Aplicacion::getInstance()->getConexionBd();

        $sql  = 'INSERT INTO Pago (dni, importe, fechaPago) VALUES (?, ?, ?)';

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new \mysqli_sql_exception($conn->error, $conn->errno);
        }

        $stmt->bind_param('sds', $dni, $importe, $fechaPago);

        $result = $stmt->execute();

        $stmt->close();

        return $result;
    }
    


    //Modificado
    public function update(TOPago $p) {

        // Variables intermedias
        $id        = $p->getId();
        $dni       = $p->getDni();
        $importe   = $p->getImporte();
        $fechaPago = $p->getFechaPago();

        
        $conn = Aplicacion::getInstance()->getConexionBd();

        $sql  = 'UPDATE Pago SET dni = ?, importe = ?, fechaPago = ? WHERE id = ?';

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            throw new \mysqli_sql_exception($conn->error, $conn->errno);
        }

        $stmt->bind_param("sdsi", $dni, $importe, $fechaPago, $id);

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
            $parking = new TOParking($id, $dni, $precio,$dir, $ciudad,$cp,$nPlazas, $img);
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
                $row['CP'] ?? null,
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
            $parkings[] = new TOParking($id, $dni, $precio, $dir, $ciudad,$cp,$nPlazas, $img);
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
