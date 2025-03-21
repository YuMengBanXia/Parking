<?php
require_once __DIR__.'/TOTicket.php';
require_once __DIR__.'/../parking/DAO.php';

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
        return $stmt->execute();
    }

    public function count($id){
        $query="SELECT COUNT(*) AS n FROM ticket WHERE id=$id";
        $result = $this->ejecutarConsulta($query);
        return $result[0]['n'];
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

    public function searchMatricula($matricula){
        $query="SELECT * FROM ticket WHERE matricula=$matricula";
        $result = $this->ejecutarConsulta($query);
        if(empty($result[0])){
            return 0;
        }
        $ticket = new TOTicket($result[0]['codigo'],$result[0]['id'],$result[0]['matricula'],$result[0]['fecha_ini']);
        return $ticket;
    }
}
?>