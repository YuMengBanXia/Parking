<?php
namespace es\ucm\fdi\aw\ePark;

class userFactory
{
    public static function CreateUser() : IUser
    {
        $userDAO = false;

        if (true)
        {
            $userDAO = new userDAO();
        }
        else
        {
            $userDAO = new userMock();
        }
        
        return $userDAO;
    }
}

?>