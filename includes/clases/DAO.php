
<?php
class DAO{
    public $mysqli;

    public function __construct(){//constructor de la clase
        if(!$this->mysqli){
            $this->mysqli=new mysqli('127.0.0.1', 'admin','admin','BBDD');//establecer una nueva conexión a BBDD
            if($this->mysqli->connect_errno){ //comprobar que se ha establecido correctamente la conexión
                echo "Fallo al conectar MySQL:(". $this->mysqli->connect_errno .")".$this->mysqli->connect_error;
            }
        }
    }
    
    public function ejecutarConsulta($sql){//Devolver los resultados de la consulta(pasado como argumento) en un array asociativo
        if($sql != ""){
            $consulta = $this->mysqli->query($sql) or die ($mysqli->error. " en la línea ".(__LINE__-1));
            $tablaDatos = array();
            while ($fila = mysqli_fetch_assoc($consulta)){ 
                array_push($tablaDatos, $fila);
            }
        return $tablaDatos;

        } else{
            return 0;
        }
    }
}

?>