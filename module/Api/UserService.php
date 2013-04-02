<?php

namespace Api;

class UserService
{
    protected $users;

    public function __construct(UserStorage $users)
    {
        $this->users = $users;
    }

    public function byEmail($email)
    {
        $user = $this->users->fetchByEmail($email);
        if (isset($user['httpStatus'])) {
            return false;
        }
        return true;
    }

    public function byUsername($username)
    {
        $user = $this->users->fetchByUsername($username);
        if (isset($user['httpStatus'])) {
            return false;
        }
        return true;
    }
}
