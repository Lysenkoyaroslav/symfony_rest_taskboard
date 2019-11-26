<?php


namespace App\Controller;

use App\Entity\Users;
use FOS\RestBundle\Controller\AbstractFOSRestController;

class TokenVerificator extends AbstractFOSRestController
{

    /**
     * Checks user`s token from request header on existence.
     *
     * @param $apiToken
     * @return string
     *
     */
    public function tokenVerification($apiToken)
    {
        $repository = $this->getDoctrine()->getRepository(Users::class);
        $user = $repository->findOneBy([
            'apiToken' => $apiToken,
        ]);


        return $user->getApiToken();

    }
}