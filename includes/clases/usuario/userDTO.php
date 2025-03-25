<?php
namespace es\ucm\fdi\aw\ePark;


class userDTO
{
    private $dni;

    private $username;

    private $password;

    public function __construct($dni, $username, $password)
    {
        $this->dni = $dni;
        $this->username = $username;
        $this->password = $password;
    }

    public function dni()
    {
        return $this->dni;
    }

    public function username()
    {
        return $this->username;
    }

    public function password()
    {
        return $this->password;
    }
}
?>