
<?php
include_once('DAO.php');


class DAOParking extends DAO{
    
    public function __construct() {
        parent::__construct();//llamar al constructor de la clase padre
    }


    public Parking[] listarParkings(){
        $query="SELECT * from parkings";
        
    }
}

?>