<?php


namespace App\Tests\_support\Helper;


use App\Service\AuthControllerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;

class TestClass extends AbstractFOSRestController implements AuthControllerInterface
{
    public function makeAction()
    {

        return true;
    }
}