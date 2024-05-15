<?php
/**
 * PHP version 8.2.12
 *
 * @category EventListener
 * @package  App\EventListener
 * @author   Tommy Brisset <tommy.brisset@supinfo.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     src/EventListener/AuthenticationListener.php
 */

namespace App\EventListener;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class AuthenticationListener
{
    private $security;
    private $router;

    public function __construct(Security $security, RouterInterface $router)
    {
        $this->security = $security;
        $this->router = $router;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY') && $request->getPathInfo() !== '/login') {
            $response = new RedirectResponse($this->router->generate('app_login'));
            $event->setResponse($response);
        }
    }
}
