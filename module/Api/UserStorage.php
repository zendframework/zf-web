<?php

namespace Api;

class UserStorage
{
    protected $data;

    public function __construct($userDetailsPath)
    {
        if (!file_exists($userDetailsPath)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid path "%s" for user detail information',
                $userDetailsPath
            ));
        }
        $data = include $userDetailsPath;
        if (!is_array($data)) {
            throw new \InvalidArgumentException(sprintf(
                'Path "%s" does not return array of user details',
                $userDetailsPath
            ));
        }

        $this->data = $data;
    }

    public function fetchByEmail($email)
    {
        $email = strtolower($email);
        $found = array_filter($this->data, function ($user) use ($email) {
            return ($user['email'] === $email);
        });
        if (0 === count($found)) {
            return array(
                'httpStatus'  => 404,
                'title'       => 'Not found',
                'description' => 'User not found',
            );
        }
        return array_shift($found);
    }

    public function fetchByUsername($username)
    {
        $username = strtolower($username);
        $found = array_filter($this->data, function ($user) use ($username) {
            return ($user['name_user'] === $username);
        });
        if (0 === count($found)) {
            return array(
                'httpStatus'  => 404,
                'title'       => 'Not found',
                'description' => 'User not found',
            );
        }
        return array_shift($found);
    }
}
