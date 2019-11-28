<?php


namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ControllerEvent;
use App\Service\AuthControllerInterface;
use App\Controller\TokenVerificator;

class TokenListener
{

    protected $tokenVerificator;


    public function __construct(TokenVerificator $tokenVerificator)
    {

        $this->tokenVerificator = $tokenVerificator;
    }


    public function onKernelController(ControllerEvent $event)
    {
        $controllerInstance = $event->getController();
        $request = $event->getRequest();

        if (is_array($controllerInstance)) {
            $controllerInstance = $controllerInstance[0];
        }


        if ($controllerInstance instanceof AuthControllerInterface) {

            $apiToken = $request->headers->get('apiToken');

            $tokenVerification = $this->tokenVerificator->tokenVerification($apiToken);

            return $tokenVerification;

        }

        return true;
    }


}