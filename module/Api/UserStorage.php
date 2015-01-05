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

        foreach ($data as $index => $user) {
            $data[$index]['name_user'] = strtolower($user['name_user']);
            $data[$index]['email']     = strtolower($user['email']);
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
