<?php
/**
 * PHP version 8.2.12
 *
 * @category           Controller
 * @package            App\Controller
 * @Entity
 * @Table(name="home")
 * @author             Tommy Brisset <tommy.brisset@supinfo.com>
 * @license            https://opensource.org/licenses/MIT MIT License
 * @link               src/Controller/HomeController.php
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render(
            'home/index.html.twig', [
            'controller_name' => 'HomeController',
            ]
        );
    }
}
