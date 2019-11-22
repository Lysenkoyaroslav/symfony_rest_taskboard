<?php

namespace App\Controller;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Users;
use App\Form\UserType;
use Symfony\Bridge\Doctrine\Tests\Fixtures\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;


/**
 * @Route("/api", name="index")
 */
class UsersController extends AbstractFOSRestController
{

    /**
     * Create Users.
     * @Rest\Post("/signup")
     *
     * @return Response
     */
    public function postUserAction(Request $request)
    {
        $user = new Users();
        $form = $this->createForm(UserType::class, $user);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
        }
        return $this->handleView($this->view($form->getErrors()));
    }

    /**
     *Lists all Users.
     * @Rest\Get("/users")
     *
     * @return Response
     */
    public function getUsersAction()
    {
        $repository = $this->getDoctrine()->getRepository(Users::class);
        $users = $repository->findall();
        return $this->handleView($this->view($users));

    }

    /**
     * Lists one User by id
     * @Rest\Get("/users/{id}")
     *
     * @return Response
     *
     */
    public function getUserByIdAction($id)
    {
        $repository = $this->getDoctrine()->getRepository(Users::class);
        $user = $repository->find($id);
        return $this->handleView($this->view($user));
    }
}