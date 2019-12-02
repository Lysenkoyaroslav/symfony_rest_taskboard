<?php

namespace App\Controller;

use App\Service\AuthControllerInterface;
use App\Entity\Users;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * @Route("/api", name="index")
 */
class UsersController extends AbstractFOSRestController implements AuthControllerInterface
{

    /**
     * Creates Users.
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
    public function getUserByIdAction($id, Request $request)
    {
        $response = new Response();
        $apiToken = $request->headers->get('apiToken');

        $repository = $this->getDoctrine()->getRepository(Users::class);
        $currentUser = $repository->findOneBy(['apiToken' => $apiToken]);
        $user = $repository->find($id);

        if (empty($user)) {
            throw new NotFoundHttpException();
        }

        if ($currentUser !== $user) {
            return $response->setContent(
                'Access denied!Only user, who created this account able to access this endpoint'
            );
        }

        return $this->handleView($this->view($user));
    }


}