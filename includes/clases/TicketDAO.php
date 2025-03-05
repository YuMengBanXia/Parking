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
        $query="INSERT INTO ticket"
    }

}
?>