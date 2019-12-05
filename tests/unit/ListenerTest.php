<?php

namespace App\Tests;

use App\Controller\TokenVerificator;
use App\EventListener\TokenListener;
use App\Tests\_support\Helper\TestClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;


class ListenerTest extends \Codeception\Test\Unit
{

    /** @var \PHPUnit\Framework\MockObject\MockObject */
    protected $tokenVerificator;

    protected $methodName = 'makeAction';


    /** @var Request */
    protected $request;
    /** @var TokenListener */
    protected $listener;


    protected function _setUp()
    {
        $this->tokenVerificator = new TokenVerificator();
        $this->request = new Request();
        $this->request->attributes->add(['_route' => 'test']);
        $this->listener = new TokenListener(
            $this->tokenVerificator
        );

    }

    public function testAccessDenied()
    {
        $this->expectException(HttpException::class);

        $event = new ControllerEvent(
            $this->createMock(HttpKernelInterface::class),
            [new TestClass(), $this->methodName],
            $this->request,
            HttpKernelInterface::MASTER_REQUEST
        );

        $this->listener->onKernelController($event);

    }
}