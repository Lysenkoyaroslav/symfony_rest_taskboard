<?php
namespace App\Controller;

use App\Service\AdminControllerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * @Route("/admin", name="admin")
 */
class AdminController extends AbstractFOSRestController implements AdminControllerInterface
{

    /**
     *
     * @Rest\Get("/test")
     *
     * @return mixed
     *
     */
    public function test(): Response
    {
        $response = new Response();
        
        return $response->setContent('You are admin!');
    }
}
