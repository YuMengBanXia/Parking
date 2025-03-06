<?php

include __DIR__ . "/../../mysql/BBDD.php";
require_once __DIR__.'/../DAO.php';

require("IUser.php");
require("userDTO.php");

class userDAO extends DAO implements IUser
{

    public $mysqli; 

    public function __construct()
    {
        parent::__construct();
    }

    public function login($userDTO)
    {
        $foundedUserDTO = $this->buscaUsuario($userDTO->dni());
        
        if ($foundedUserDTO && $foundedUserDTO->password() === $userDTO->password()) 
        {
            return $foundedUserDTO;
        } 

        return false;
    }

    private function buscaUsuario($dni)
    {
        $query = sprintf("SELECT usuario, contrasena, dni FROM usuario WHERE dni = $dni");
        
        $rs = $this->ejecutarConsulta($query);

        if(!empty($rs))
        {
            $fila = $rs[0];
            
            $user = new userDTO($fila['dni'], $fila['usuario'], $fila['contrasena']);
            
            return $user;
        }

        return false;
    }

    public function create($userDTO)
    {
        $createdUserDTO = false;

        $dniUser = $userDTO->dni();
        $userName = $userDTO->userName();
        $password = $userDTO->password();

        $query = "INSERT INTO usuario (usuario, contrasena, dni) VALUES (?, ?, ?)";
        $stmt = $this->mysqli->prepare($query);
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->mysqli->error);
        }
        else {
            $createdUserDTO = new userDTO($dniUser, $userName, $password);
            $stmt->bind_param("sss", $userName, $password, $dniUser);
        }

        return $createdUserDTO;
    }

}
?>