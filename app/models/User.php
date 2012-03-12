<?php

class User
{
    public $name = null;
    public $username = null;
    public $email = null;
    
    public function isAdministrator()
    {
        if (in_array($this->username, array('ralph', 'matthew', 'zimuel', 'zeev'))) {
            return true;
        }
        
        // everyone else
        return false;
    }
}