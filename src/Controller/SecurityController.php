<?php
namespace App\Controller;

use App\Service\TokenGenerator;
use App\Entity\Users;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractFOSRestController
{
    /**
     * @Route("/login", name="app_login", methods={"POST"})
     * @return Response
     */
    public function login(Request $request, TokenGenerator $tokenGenerator)
    {
        $response = new Response();
        $data = json_decode($request->getContent(), true);

        if (!isset($data['userName']) || !isset($data['password'])) {
            return $response->setContent('"userName" and "password" are required fields!');
        }

        $repository = $this->getDoctrine()->getRepository(Users::class);
        $user = $repository->findOneBy([
                'userName' => $data['userName'],
                'password' => $data['password']
            ]
        );

        if (!$user) {
            
            return $response->setContent('Access denied!');
        }

        $em = $this->getDoctrine()->getManager();
        $apiToken = $tokenGenerator->generateToken($user);
        $user->setApiToken($apiToken);
        $em->persist($user);
        $em->flush();

        return $response->setContent($apiToken);
    }
}
