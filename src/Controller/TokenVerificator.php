<?php


namespace App\Controller;

use App\Entity\Users;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        if (empty($apiToken)) {
            throw new HttpException(401, 'You need to signin application to access this endpoint');
        }

        $repository = $this->getDoctrine()->getRepository(Users::class);
        $user = $repository->findOneBy([
            'apiToken' => $apiToken,
        ]);

        if (empty($user)) throw new HttpException(401, 'Your token is invalid!');
        else return $user;

    }
}