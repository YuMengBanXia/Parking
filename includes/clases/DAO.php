
<?php
require_once __DIR__.'/../mysql/BBDD.php';
class DAO{
    public $bd;
    public $mysqli;

    public function __construct(){//constructor de la clase
        
        if(!$this->mysqli){
            
            $this->mysqli=new BBDD('127.0.0.1', 'root','','practica'); //Primera ejecucion
            $this->mysqli=new mysqli('127.0.0.1', 'root','','practica');//establecer una nueva conexión a BBDD
            if($this->mysqli->connect_errno){ //comprobar que se ha establecido correctamente la conexión
                echo "Fallo al conectar MySQL:(". $this->mysqli->connect_errno .")".$this->mysqli->connect_error;
            }
            
            
            
        }
    }
    
    public function ejecutarConsulta($sql){//Devolver los resultados de la consulta(pasado como argumento) en un array asociativo
        if($sql != ""){
            try{
                $consulta = $this->mysqli->query($sql) or die ($this->mysqli->error. " en la línea ".(__LINE__-1));
                $tablaDatos = array();
                while ($fila = mysqli_fetch_assoc($consulta)){ 
                    array_push($tablaDatos, $fila);
                }
                return $tablaDatos;
            }
            catch(mysqli_sql_exception $e){
                echo "".$e->getMessage()."";
            }

        } else{
            return 0;
        }
    }
    
}

?>