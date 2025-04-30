<?php
namespace es\ucm\fdi\aw\ePark;

class TicketDAO extends DAO{
    public static $instancia;
    public $mysqli; 

    public static function getSingleton() { //Patrón Singleton para única instancia de la clase
        if ( !self::$instancia instanceof self) { 
            self::$instancia = new self; 
        } 
        return self::$instancia; 
    }
    
    
    //Modificado
    public function lastCodigo($id) {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = "SELECT MAX(codigo) FROM `Ticket` WHERE idParking = ?";

        $stmt = $conn->prepare($query);
    
        $stmt->bind_param("i", $id);

        $stmt->execute();
    
        $stmt->bind_result($codigo);

        if($stmt->fetch()){

            $stmt->close();

            return $codigo;
        }

        $stmt->close();
    
        return 0;
    }
    
    
    //Modificado
    public function insert($id, $matricula){
        $fecha = new \DateTime()->format('Y-m-d H:i:s');

        $conn = Aplicacion::getInstance()->getConexionBd();

        $query="INSERT INTO Ticket (idParking,matricula,fecha_ini) VALUES (?,?,?,?)";
        
        $stmt = $conn->prepare($query);

        $stmt->bind_param("iss", $id, $matricula, $fecha);

        $resultado=$stmt->execute();

        $stmt->close();
  
        return $resultado;
    }
    

//Modificado
    public function ocupacion($id){
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query="SELECT COUNT(*) FROM Ticket WHERE idParking=?";

        $stmt = $conn->prepare($query);

        $stmt->bind_param("i",$id);

        $stmt->execute();

        $stmt->bind_result($num);

        $stmt->fetch();
        
        $stmt->close();

        return $num;
    }

    public function delete($codigo,$id){
        $query = "DELETE FROM Ticket WHERE id=? AND codigo=?";
        $stmt = $this->mysqli->prepare($query);
        
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->mysqli->error);
        }
        $stmt->bind_param("ii",$id,$codigo);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();

        return $affected > 0;
        
    }

    //Modificado
    public function searchMatricula($matricula) {

        $conn = Aplicacion::getInstance()->getConexionBd();
    
        $query = "SELECT codigo, idParking, matricula, fecha_ini FROM Ticket WHERE matricula = ?";

        $stmt = $conn->prepare($query);

        $stmt->bind_param("s", $matricula);

        $stmt->execute();
    
        // Declaras las variables donde se guardará el resultado
        $stmt->bind_result($codigo, $id, $mat, $fecha);
    
        if ($stmt->fetch()) {

            $stmt->close();
            
            return new TOTicket($codigo, $id, $mat, new \DateTime($fecha));

        } else {

            $stmt->close();

            return null; // No se encontró nada
        }
    }

    public function searchCodigo($codigo){
        $conn = Aplicacion::getInstance()->getConexionBd();
    
        $query = "SELECT codigo, idParking, matricula, fecha_ini FROM Ticket WHERE codigo = ?";

        $stmt = $conn->prepare($query);

        $stmt->bind_param("i", $codigo);

        $stmt->execute();
    
        // Declaras las variables donde se guardará el resultado
        $stmt->bind_result($codigo, $id, $mat, $fecha);
    
        if ($stmt->fetch()) {

            $stmt->close();
            
            return new TOTicket($codigo, $id, $mat, new \DateTime($fecha));

        } else {

            $stmt->close();

            return null; // No se encontró nada
        }
    }
}
?>