<?php


namespace App\EventListener;


use App\Controller\TokenVerificator;
use App\Service\AdminControllerInterface;
use App\Service\RoleDeterminator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class AdminListener
{

    protected $tokenVerificator;
    protected $roleDeterminator;

    public function __construct(TokenVerificator $tokenVerificator, RoleDeterminator $roleDeterminator)
    {

        $this->tokenVerificator = $tokenVerificator;
        $this->roleDeterminator = $roleDeterminator;
    }


    public function onKernelController(ControllerEvent $event)
    {
        $response = new Response();
        $controllerInstance = $event->getController();
        $request = $event->getRequest();

        if (is_array($controllerInstance)) {
            $controllerInstance = $controllerInstance[0];
        }


        if ($controllerInstance instanceof AdminControllerInterface) {

            $apiToken = $request->headers->get('apiToken');
            $user = $this->tokenVerificator->tokenVerification($apiToken);

            $role = $this->roleDeterminator->roleDetermination($user);

            if ($role !== 'Admin') return $response->setContent('Access denied!(For admin only)');
        }

        return true;
    }
}