<?php
namespace es\ucm\fdi\aw\ePark;

class userMock implements IUser
{
    public function login($userDTO)
    {
        return true;
    }

    public function create($userDTO)
    {
        return true;
    }

}
?>