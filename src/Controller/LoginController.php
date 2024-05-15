<?php
/**
 * PHP version 8.2.12
 *
 * @category Controller
 * @package  App\Controller
 * @author   Tommy Brisset <tommy.brisset@supinfo.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     src/Controller/LoginController.php
 */

namespace App\Controller;


use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\User\UserInterface;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils, JWTTokenManagerInterface $jwtManager, Request $request): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastEmailAddress = $authenticationUtils->getLastUsername();

        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if ($request->isMethod('POST')) {
            /**
 * @var UserInterface $user 
*/
            $user = $this->getUser();

            if (!$user) {
                throw $this->createNotFoundException('Utilisateur non trouvé.');
            }

            $token = $jwtManager->create($user);

            $response = new Response();
            $response->headers->setCookie(
                new Cookie(
                    'JWT_TOKEN', // Nom du cookie
                    $token, // Contenu du cookie (token JWT)
                    (new \DateTime())->modify('+24 hour'),
                    '/', // Chemin du cookie
                    null, // Domaine
                    false, // Sécurisé
                    true, // HttpOnly
                    false, // Raw
                    'Strict' // SameSite
                )
            );

            $response->send();

            return $this->redirectToRoute('home');
        }

        return $this->render(
            'login/index.html.twig', [
            'last_email_address' => $lastEmailAddress,
            'error' => $error
            ]
        );
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // rien a mettre ici car c'est géré par Symfony !
    }

}
