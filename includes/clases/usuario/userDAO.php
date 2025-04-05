<?php
namespace es\ucm\fdi\aw\ePark;

class userDAO implements IUser
{
    public $mysqli;

    public function __construct()
    {
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
            error_log("Error en la preparación de la consulta: " . $this->mysqli->error);
            return false;
        }
        
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($fila = $result->fetch_assoc()) {
            $user = new userDTO($fila['dni'], $fila['nomUsuario'], $fila['contrasenia'], $fila['tipoUsuario']);
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
        $tipoUsuario = $userDTO->tipoUsuario();

        // Verificar si el usuario ya existe
        $existingUser = $this->existeUsuario($dniUser, $userName);
        if ($existingUser) {
            error_log("El el DNI: " . $dniUser . " o el usuario" . $userName . " ya existe");
            throw new userAlreadyExistException("El el DNI: " . $dniUser . " o el usuario" . $userName . " ya existe");
        }

        $query = "INSERT INTO usuario (dni, nomUsuario, contrasenia, tipoUsuario) VALUES (?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($query);

        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->mysqli->error);
        }

        $stmt->bind_param("ssss", $dniUser, $userName, $hashedPassword, $tipoUsuario);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                // Solo crear el objeto si la inserción fue exitosa
                $createdUserDTO = new userDTO($dniUser, $userName, $password, $tipoUsuario);
            }
        } 
        else {
            error_log("Error en la ejecución de la consulta: " . $stmt->error);
        }

        $stmt->close(); // Cerrar la consulta preparada
        return $createdUserDTO;
    }

    private function existeUsuario($dniUser, $userName)
    {
        $query = "SELECT * FROM usuario WHERE dni = ? OR nomUsuario = ?";
        $stmt = $this->mysqli->prepare($query);
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->mysqli->error);
        }
        $stmt->bind_param("ss", $dniUser, $userName);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close(); // Cerrar la consulta preparada

        if ($result->num_rows > 0) {
            return true; // El usuario ya existe
        }

        return false; // El usuario no existe

    }
}
