<?php

namespace es\ucm\fdi\aw\ePark;

class userAppService
{
    private static $instance;

    private function __construct() {}

    public static function GetSingleton()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
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

        return $createdUserDTO;
    }
    
}
