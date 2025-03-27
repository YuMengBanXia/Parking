<?php
namespace es\ucm\fdi\aw\ePark;

interface IUser
{
    public function login($userDTO);

    public function create($userDTO);
}
?>