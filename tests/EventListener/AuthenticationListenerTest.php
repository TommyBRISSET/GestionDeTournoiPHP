<?php

namespace App\Tests\EventListener;

use App\EventListener\AuthenticationListener;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;

class AuthenticationListenerTest extends TestCase
{
    private $security;
    private $router;
    private $listener;

    protected function setUp(): void
    {
        $this->security = $this->createMock(Security::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->listener = new AuthenticationListener($this->security, $this->router);
    }

    public function testRequestAuthenticatedUser(): void
    {
        $this->security->method('isGranted')->with('IS_AUTHENTICATED_FULLY')->willReturn(true);

        $event = $this->createMock(RequestEvent::class);
        $event->method('getRequest')->willReturn($this->createMock(Request::class));

        $this->listener->onKernelRequest($event);

        $this->assertTrue(true);
    }

    public function testUnauthenticatedUser(): void
    {
        $this->security->method('isGranted')->with('IS_AUTHENTICATED_FULLY')->willReturn(false);

        $request = $this->createMock(Request::class);
        $request->method('getPathInfo')->willReturn('/login');

        $event = $this->createMock(RequestEvent::class);
        $event->method('getRequest')->willReturn($request);

        $this->listener->onKernelRequest($event);

        $this->assertTrue(true);
    }

    public function testUnauthenticatedUserNoLoginPage(): void
    {
        $this->security->method('isGranted')->with('IS_AUTHENTICATED_FULLY')->willReturn(false);

        $request = $this->createMock(Request::class);
        $request->method('getPathInfo')->willReturn('/not-login');

        $event = $this->createMock(RequestEvent::class);
        $event->method('getRequest')->willReturn($request);

        $this->router->method('generate')->with('app_login')->willReturn('/login');
        $event->expects($this->once())->method('setResponse')->with($this->isInstanceOf(RedirectResponse::class));

        $this->listener->onKernelRequest($event);
    }
}