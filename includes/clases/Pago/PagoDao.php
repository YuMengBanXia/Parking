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
        $idParking  = $p->getIdParking();
        $importe   = $p->getImporte();
        $fechaPago = $p->getFechaPago();

        $conn = Aplicacion::getInstance()->getConexionBd();

        $sql  = 'INSERT INTO Pago (dni, idParking,importe, fechaPago) VALUES (?, ?, ?, ?)';

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new \mysqli_sql_exception($conn->error, $conn->errno);
        }

        $stmt->bind_param('sids', $dni,$idParking, $importe, $fechaPago);

        $result = $stmt->execute();

        $stmt->close();

        return $result;
    }
    


    //Modificado
    public function update(TOPago $p) {

        // Variables intermedias
        $id        = $p->getId();
        $dni       = $p->getDni();
        $idParking  = $p->getIdParking();
        $importe   = $p->getImporte();
        $fechaPago = $p->getFechaPago();

        
        $conn = Aplicacion::getInstance()->getConexionBd();

        $sql  = 'UPDATE Pago SET dni = ?, idParking = ?, importe = ?, fechaPago = ? WHERE id = ?';

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            throw new \mysqli_sql_exception($conn->error, $conn->errno);
        }

        $stmt->bind_param("sidsi", $dni, $idParking, $importe, $fechaPago, $id);

        $resultado = $stmt->execute();

        $stmt->close();

        return $resultado;
    }


    //Modificado
    public function delete($id) {

        $conn = Aplicacion::getInstance()->getConexionBd();

        $sql = "DELETE FROM Pago WHERE id = ?";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            throw new \mysqli_sql_exception($conn->error, $conn->errno);
        }   

        $stmt->bind_param("i", $id);

        $resultado = $stmt->execute();

        $stmt->close();

        return $resultado;
    }

    //Modificado
    public function getById($id) {

        $conn = Aplicacion::getInstance()->getConexionBd();

        $sql  = "SELECT id, dni, importe, DATE(fechaPago) AS fechaPago FROM Pago WHERE id = ?";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new \mysqli_sql_exception($conn->error, $conn->errno);
        }

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $stmt->bind_result($rowId, $dni, $importe, $fechaPago);

        $pago = null;

        if ($stmt->fetch()) {
            $pago = new TOPago($rowId, $dni, (float)$importe, $fechaPago);
        }
        $stmt->close();

        return $pago;
        
    }

    
    public function getAll() {
    
        $conn = Aplicacion::getInstance()->getConexionBd();
    
        $sql  = "SELECT id, dni, importe, DATE(fechaPago) AS fechaPago FROM Pago";
    
        $result = $conn->query($sql);
    
        $pagos = [];
    
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $pagos[] = new TOPago(
                    (int)$row['id'], 
                    $row['dni'], 
                    (float)$row['importe'], 
                    $row['fechaPago']
                );
            }
            $result->free();
        }
        return $pagos;// Devuelve un array vacío si no hay parkings disponibles
        
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
    
    public function listarPorRangoFecha(string $inicio, string $fin): array {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql  = "SELECT 
                    p.id, 
                    p.dni, 
                    p.idParking, 
                    p.importe, 
                    DATE(p.fechaPago) AS fechaPago
                 FROM Pago AS p
                 WHERE p.fechaPago BETWEEN ? AND ?
                 ORDER BY p.fechaPago ASC";
    
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new \mysqli_sql_exception($conn->error, $conn->errno);
        }
    
        $desde = $inicio . ' 00:00:00';
        $hasta = $fin    . ' 23:59:59';
        $stmt->bind_param('ss', $desde, $hasta);
        $stmt->execute();
    
        // Ahora vinculamos TAMBIÉN idParking
        $stmt->bind_result($rowId, $dni, $idParking, $importe, $fechaPago);
    
        $pagos = [];
        while ($stmt->fetch()) {
            // Y pasamos 5 argumentos al constructor
            $pagos[] = new TOPago(
                $rowId,
                $dni,
                (int)   $idParking,
                (float) $importe,
                $fechaPago
            );
        }
        $stmt->close();
        return $pagos;
    }
    

    public function listarPorRangoFechaYPropietario(
        string $inicio,
        string $fin,
        string $dniPropietario
    ): array {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql  = '
            SELECT p.id, p.dni, p.idParking, DATE(p.fechaPago) AS fechaPago, p.importe
            FROM Pago AS p
            JOIN Parking AS pk
              ON p.idParking = pk.id
            WHERE pk.dni = ?
              AND p.fechaPago BETWEEN ? AND ?
            ORDER BY p.fechaPago ASC
        ';
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new \mysqli_sql_exception($conn->error, $conn->errno);
        }
        $desde = $inicio . ' 00:00:00';
        $hasta = $fin    . ' 23:59:59';
        $stmt->bind_param('sss',
            $dniPropietario,
            $desde,
            $hasta
        );
        $stmt->execute();
        $stmt->bind_result($rowId, $dni, $idParking, $fechaPago, $importe);

        $pagos = [];
        while ($stmt->fetch()) {
            $pagos[] = new TOPago(
                $rowId,
                $dni,
                $idParking,
                (float)$importe,
                $fechaPago
            );
        }
        $stmt->close();
        return $pagos;
    }
}

?>
