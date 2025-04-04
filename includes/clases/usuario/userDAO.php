<?php

namespace es\ucm\fdi\aw\ePark;

class userDAO extends DAO implements IUser
{

    public $mysqli;
    public static $instancia;

    public static function getSingleton()
    { //Patrón Singleton para única instancia de la clase
        if (!self::$instancia instanceof self) {
            self::$instancia = new self;
        }
        return self::$instancia;
    }

    public function __construct()
    {
        parent::__construct();
        $this->mysqli = Aplicacion::getInstance()->getConexionBd(); // Inicializa la conexión
    }

    public function login($userDTO)
    {
        $foundedUserDTO = $this->buscaUsuario($userDTO->nomUsuario());

        if ($foundedUserDTO && password_verify($userDTO->contrasenia(), $foundedUserDTO->contrasenia())) {
            return $foundedUserDTO;
        }

        return false;
    }

    private function buscaUsuario($username)
    {
        $query = "SELECT dni, nomUsuario, contrasenia, tipoUsuario FROM Usuario WHERE nomUsuario = ?";
        $stmt = $this->mysqli->prepare($query);
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->mysqli->error);
        }
        $stmt->bind_param("s", $username); // Vincula el valor de $username al marcador ?
        $stmt->execute(); // Ejecuta la consulta
        $result = $stmt->get_result(); // Obtiene los resultados

        if ($fila = $result->fetch_assoc()) {
            $user = new userDTO($fila['dni'], $fila['nomUsuario'], $fila['contrasenia'], null);
            $result->free();
            $stmt->close();
            return $user;
        }
        $result->free();
        $stmt->close();
        return false;
    }

    public function create($userDTO)
    {
        $createdUserDTO = false;

        $dniUser = $userDTO->dni();
        $userName = $userDTO->nomUsuario();
        $password = $userDTO->contrasenia();
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        /*
        if($this->buscaUsuario($userName)){
            return false;
        }
        */
        $query = "INSERT INTO usuario (dni, nomUsuario, contrasenia, tipoUsuario) VALUES (?, ?, ?, b'1')";
        $stmt = $this->mysqli->prepare($query);

        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->mysqli->error);
        }

        $stmt->bind_param("sss", $dniUser, $userName, $hashedPassword); // Vincula los valores a los marcadores ?

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                // Solo crear el objeto si la inserción fue exitosa
                $createdUserDTO = new userDTO($dniUser, $userName, $password, b'1');
            }
        } else {
            error_log("Error en la ejecución de la consulta: " . $stmt->error);
        }

        $stmt->close(); // Cerrar la consulta preparada
        return $createdUserDTO;
    }
}
