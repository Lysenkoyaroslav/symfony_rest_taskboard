<?php


namespace App\Service;


class TokenGenerator
{
    /**
     * Generate token for authenticated User
     *
     * @param $user
     * @return string
     *
     */
    public function generateToken($user)
    {

        $now = date("Y-m-d H:i:s");
        $strToken = $user->getId() . $now;
        $token = hash('md5', $strToken);

        return $token;
    }
}