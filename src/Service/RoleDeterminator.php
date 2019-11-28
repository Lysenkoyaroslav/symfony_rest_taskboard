<?php


namespace App\Service;


class RoleDeterminator
{
    public function roleDetermination($user)
    {
        $role = $user->getRoles();
        $role = $role->getRoles();

        return $role;
    }
}