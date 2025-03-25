<?php
namespace es\ucm\fdi\aw\ePark;

require_once __DIR__ . "/userFactory.php";

class userAppService
{
    private static $instance;
    private static $daoUsuario;

    public static function inicializar(){
        self::$daoUsuario=userDAO::getSingleton();
    }

    public static function GetSingleton()
    {
        if (!self::$instance instanceof self)
        {
            self::$instance = new self;
        }

        return self::$instance;
    }
  
    private function __construct()
    {
    } 

    public static function login($userDTO)
    {
        $IUserDAO = userFactory::CreateUser();

        $foundedUserDTO = $IUserDAO->login($userDTO);

        return $foundedUserDTO;
    }

    public static function create($userDTO)
    {
        $IUserDAO = userFactory::CreateUser();

        $createdUserDTO = $IUserDAO->create($userDTO);

        $usuario = new userDTO($userDTO->dni(), $userDTO->username(), $userDTO->password());

        return $createdUserDTO;
    }

}

?>