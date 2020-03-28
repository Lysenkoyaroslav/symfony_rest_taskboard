<?php
namespace App\Controller;

use App\Entity\Status;
use App\Entity\Users;
use App\Form\UserType;
use App\Service\StatusInterface;
use App\Service\TokenGenerator;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class RegistrationController extends AbstractFOSRestController implements StatusInterface
{
    /**
     * Creates Users.
     * @Rest\Post("/registration")
     *
     * @return Response
     */
    public function getData(Request $request, TokenGenerator $tokenGenerator)
    {
        $response = new Response();
        $user = new Users();
        $form = $this->createForm(UserType::class, $user);
        $data = json_decode($request->getContent(), true);

        if (!isset($data['userName']) || !isset($data['password']) || !isset($data['email'])) {
            
            return $response->setContent('Fill in required fields: userName, password, email!');
        }

        $statusRepository = $this->getDoctrine()->getRepository(Status::class);
        $status = $statusRepository->findOneBy(['name' => self::UNVERIFIED]);
        $repository = $this->getDoctrine()->getRepository(Users::class);
        $userName = $repository->findOneBy(['userName' => $data['userName']]);
        $userEmail = $repository->findOneBy(['email' => $data['email']]);

        if (!empty($userName)) return $response->setContent('User name already exists');
        if (!empty($userEmail)) return $response->setContent('Email address already exists');

        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $temporaryToken = $tokenGenerator->generateTemporaryToken();
            $user->setTemporaryToken($temporaryToken);
            $user->setStatus($status);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->forward('App\Controller\PdfController::createPdf', [
                'userName' => $user->getUserName(),
                'email' => $user->getEmail()
            ]);
            $response = $this->forward('App\Controller\MailerController::sendEmail', [
                'temporaryToken' => $temporaryToken,
                'userName' => $user->getUserName()
            ]);
            
            return $response;
        }
        
        return $this->handleView($this->view($form->getErrors()));
    }

    /**
     * Verifies Users.
     * @Rest\Get("/registration/{temporaryToken}")
     *
     * @return Response
     */
    public function verifyUser(string $temporaryToken, TokenGenerator $tokenGenerator)
    {
        $response = new Response();

        $statusRepository = $this->getDoctrine()->getRepository(Status::class);
        $status = $statusRepository->findOneBy(['name' => self::VERIFIED]);
        $userRepository = $this->getDoctrine()->getRepository(Users::class);
        $user = $userRepository->findOneBy(['temporaryToken' => $temporaryToken]);

        if (empty($user)) return $response->setContent('Invalid url!');

        $apiToken = $tokenGenerator->generateToken($user);
        $user->setApiToken($apiToken);
        $user->setStatus($status);
        $user->setTemporaryToken(null);
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $response->setContent('Registration success!Your access token: ' . $apiToken);
    }
}
