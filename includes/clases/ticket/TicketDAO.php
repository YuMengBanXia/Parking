<?php
require_once __DIR__.'/TOTicket.php';
require_once __DIR__.'/../DAO.php';

class TicketDAO extends DAO{
    public static $instancia;
    public $mysqli; 

    public static function getSingleton() { //Patrón Singleton para única instancia de la clase
        if ( !self::$instancia instanceof self) { 
            self::$instancia = new self; 
        } 
        return self::$instancia; 
    }

    public function lastCodigo($id){
        $query="SELECT MAX(codigo) AS curr FROM `ticket` WHERE id=?";
        $stmt-> $this->mysqli->prepare($query);
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->mysqli->error);
        }
        $stmt->bind_param("i",$id);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $result->free();
        $stmt->close();
        if(empty($result[0]['curr'])){
            return 1;
        }
        return $result[0]['curr'];
    }

    public function insert(TOticket $ticket){
        $query="INSERT INTO ticket (codigo,id,matricula,fecha_ini) VALUES (?,?,?,?)";
        $stmt = $this->mysqli->prepare($query);
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->mysqli->error);
        }

        $cod = $ticket->get_codigo();
        $id = $ticket->get_id();
        $matricula = $ticket->get_matricula();
        $fecha = $ticket->get_fecha()->format('Y-m-d H:i:s');

        $stmt->bind_param("iiss", $cod, $id, $matricula, $fecha);
        $resultado = $stmt->execute();
        $stmt->close();
        return $stmt->execute();
    }

    public function count($id){
        $query="SELECT COUNT(*) AS n FROM ticket WHERE id=?";
        $stmt->$this->mysqli->prepare($query);
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->mysqli->error);
        }
        $stmt->bind_param("i",$id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $result->free();
        $stmt->close();
        return $row['n'];
    }

    public function delete($codigo,$id){
        $query = "DELETE FROM ticket WHERE id=? AND codigo=?";
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

    public function searchMatricula($matricula){
        $query="SELECT * FROM ticket WHERE matricula=?";
        $stmt->$this->mysqli->prepare($query);
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->mysqli->error);
        }
        $stmt->bind_param("s",$matricula);
        $stmt->execute();

        $result = $stmt->get_result();
        if($row = $result->fetch_assoc()){
            $ticket = new TOTicket($row['codigo'], $row['id'], $row['matricula'], $row['fecha_ini']);
            $result->free();
            $stmt->close();
            return $ticket;
        }
        $result->free();
        $stmt->close();
        return 0;
    }
}
?>