<?php

class BBDD{

    public $ip;
    public $user;
    public $pass; 
    public $BD;
    public $db;

    public function __construct($IP, $USER, $PASS, $BdbD) {
         
         $this->ip = $IP;
         $this->user = $USER;
         $this->pass = $PASS;
         $this->BD = $BdbD;

        try {
            // Intentar establecer la conexión a la base de datos
            self::setConnection($IP, $USER, $PASS);
            // Crear la base de datos
            self::createBdbD();
            // Crear las tablas 
            self::createTables();
            // Insertar registros iniciales
            self::insertarParkings();
        } catch (mysqli_sql_exception $e) {
            // Registrar el error en un archivo log
            echo "No se puedo realizar la petición debido a: ";
            echo "". $e->getMessage() ."";
    }
}
    

    private function setConnection($IP, $USER, $PASS){
        $this->db= @mysqli_connect($IP,$USER,$PASS,);
        if(!$this->db){
            printf("Error%d:%s.<br/>",mysqli_connect_errno(),mysqli_connect_error());
        }
    }
    private function createBdbD() {
        // Crear base de datos solo si no existe
        $sql = "CREATE DATABASE IF NOT EXISTS " . $this->BD;
        if (!(mysqli_query($this->db, $sql))) {
            echo "Error creating database: " . mysqli_error($this->db) . "<br>";
        } 
            
    }
    

    //Creación de las tablas
    private function createTables(){
        mysqli_select_db($this->db, $this->BD);
        
        
        //Tabla parking
        $sql = "CREATE TABLE IF NOT EXISTS parkings (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            dir VARCHAR(100) NOT NULL,
            ciudad VARCHAR(100) NOT NULL,
            UNIQUE (dir,ciudad), 
            CP DECIMAL(5,0) NOT NULL,
            precio DECIMAL(5,4) NOT NULL,
            n_plazas INT NOT NULL)";
         if(!(mysqli_query($this->db,$sql))){
            echo "Error table parkings: ".mysqli_error($this->db);
        }
        

        //Tabla usuarios
        $sql = "CREATE TABLE IF NOT EXISTS usuario (
            usuario VARCHAR(99) UNIQUE NOT NULL,
            contrasena VARCHAR(99) NOT NULL,
            dni VARCHAR(9) PRIMARY KEY,
            email VARCHAR(99),
            tipo ENUM('admin','cliente') DEFAULT 'cliente')";
        if(!(mysqli_query($this->db,$sql))){
            echo "Error table usuario: ".mysqli_error($this->db);
        }

        //Tabla ticket
        $sql = "CREATE TABLE IF NOT EXISTS ticket (
            codigo INT UNSIGNED,
            id INT REFERENCES parking,
            matricula VARCHAR(7),
            fecha_ini DATETIME NOT NULL,
            PRIMARY KEY (codigo,id))";
         if(!(mysqli_query($this->db,$sql))){
            echo "Error table ticket: ".mysqli_error($this->db);
        }

        //Tabla abonado
        $sql = "CREATE TABLE IF NOT EXISTS abonado (
            dni VARCHAR(9) REFERENCES usuario,
            id INT REFERENCES parking,
            matricula VARCHAR(7),
            banco VARCHAR(99) NOT NULL,
            num INT NOT NULL references plaza,
            PRIMARY KEY(dni,id))";
        if(!(mysqli_query($this->db,$sql))){
            echo "Error table abonado: ".mysqli_error($this->db);
        }

        //Tabla plaza
        $sql = "CREATE TABLE IF NOT EXISTS plaza (
            num INT UNSIGNED,
            id INT REFERENCES parking,
            ocupado BIT DEFAULT 0,
            PRIMARY KEY(num,id))";
         if(!(mysqli_query($this->db,$sql))){
            echo "Error table plaza: ".mysqli_error($this->db);
        }

        //Tabla reserva
        $sql = "CREATE TABLE IF NOT EXISTS reserva (
            dni VARCHAR(9) REFERENCES usuario,
            id INT REFERENCES parking,
            matricula VARCHAR(7),
            fecha DATE,
            num INT NOT NULL references plaza,
            PRIMARY KEY(dni,id,fecha))";
       if(!(mysqli_query($this->db,$sql))){
        echo "Error table reserva: ".mysqli_error($this->db);
    }

        
    }

    private function insertarParkings(){
           //Tabla plaza
           $sql = "INSERT IGNORE INTO `parkings` (`id`, `dir`, `ciudad`, `CP`, `precio`, `n_plazas`) VALUES
                (1, 'Calle Juan', 'Madrid', 12345, 9.9999, 100),
                (2, 'Calle Valvanera', 'Madrid', 12345, 9.9999, 100);";
        if(!(mysqli_query($this->db,$sql))){
            echo "Error al insertar algunos parkings: ".mysqli_error($this->db);
        }

        @mysqli_close($this->db);
    }
    






}


