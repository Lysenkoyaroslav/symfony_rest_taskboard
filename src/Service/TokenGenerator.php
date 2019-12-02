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
        $token = hash('sha512', $strToken);

        return $token;
    }

    public function generateTemporaryToken()
    {
        $token = bin2hex(random_bytes(16));

        return $token;
    }
}