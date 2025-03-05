<?php
require_once __DIR__.'/TOTicket.php';
require_once __DIR__.'/DAO.php';

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
        $query="SELECT MAX(codigo) AS curr FROM `ticket` WHERE id=$id";
        $result = $this->ejecutarConsulta($query);
        if(empty($result)){
            return 1;
        }
        return $result['curr'];
    }

    public function insert(TOticket $ticket){
        $query="INSERT INTO ticket (codigo,id,matricula,fecha_ini) VALUES ('$ticket->get_codigo()','$ticket->get_id','$ticket->get_matricula()','$ticket->get_fecha()')";
        $stmt = $this->mysqli->prepare($query);
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->mysqli->error);
        }
        return $stmt->execute();
    }

    public function count($id){
        $query="SELECT COUNT(*) AS n FROM ticket WHERE id=$id";
        $result = $this->ejecutarConsulta($query);
        return $result['n'];
    }

    public function delete($codigo,$id){
        $query = "DELETE FROM ticket WHERE id=$id AND codigo=$codigo";
        $stmt = $this->mysqli->prepare($query);
    
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->mysqli->error);
        }
        $resultado = $stmt->execute();
    
        if ($resultado) {
            return $stmt->affected_rows > 0; //Retorna true si se eliminó correctamente
        } else {
            return 0; //Si no se eliminó nada, retorna 0
        }
    }
}
?>