<?php

namespace es\ucm\fdi\aw\ePark;

class userDTO
{
    // DNI del usuario varchar(9) PK
    private $dni;

    // Nombre de usuario varchar(99) UNIQUE
    private $nomUsuario;

    // Contraseña del usuario varchar(99)
    private $contrasenia;

    // Tipo de usuario bit(1) 0: Cliente, 1: Propietario
    private $tipoUsuario;

    public function __construct($dni, $nomUsuario, $contrasenia, $tipoUsuario)
    {
        $this->dni = $dni;
        $this->nomUsuario = $nomUsuario;
        $this->contrasenia = $contrasenia;
        $this->tipoUsuario = $tipoUsuario;
    }

    public function dni()
    {
        return $this->dni;
    }

    public function nomUsuario()
    {
        return $this->nomUsuario;
    }

    public function contrasenia()
    {
        return $this->contrasenia;
    }

    public function tipoUsuario()
    {
        return $this->tipoUsuario;
    }
}
