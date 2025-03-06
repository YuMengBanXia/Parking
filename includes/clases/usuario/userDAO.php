<?php

include __DIR__ . "/../../mysql/BBDD.php";
require_once __DIR__ . '/../DAO.php';

require("IUser.php");
require("userDTO.php");

class userDAO extends DAO implements IUser
{

    public $mysqli;
    public static $instancia;

    public static function getSingleton() { //Patrón Singleton para única instancia de la clase
        if ( !self::$instancia instanceof self) { 
            self::$instancia = new self; 
        } 
        return self::$instancia; 
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function login($userDTO)
    {
        $foundedUserDTO = $this->buscaUsuario($userDTO->username());

        if ($foundedUserDTO && $foundedUserDTO->password() === $userDTO->password()) {
            return $foundedUserDTO;
        }

        return false;
    }

    private function buscaUsuario($username)
    {
        $query = "SELECT dni, usuario, contrasena FROM usuario WHERE usuario = '$username'";

        $rs = $this->ejecutarConsulta($query);

        if (!empty($rs)) {
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

        $stmt->bind_param("sss", $userName, $password, $dniUser);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                // Solo crear el objeto si la inserción fue exitosa
                $createdUserDTO = new userDTO($dniUser, $userName, $password);
            }
        } else {
            error_log("Error en la ejecución de la consulta: " . $stmt->error);
        }

        $stmt->close(); // Cerrar la consulta preparada
        return $createdUserDTO;
    }
}
