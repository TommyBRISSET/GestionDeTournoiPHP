<?php
/**
 * PHP version 8.2.12
 *
 * @category Controller
 * @package  App\Controller\Admin
 * @author   Tommy Brisset <tommy.brisset@supinfo.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     src/Controller/Admin/DashboardController.php
 */

namespace App\Controller\Admin;

use App\Controller\TournamentController;
use App\Entity\Registration;
use App\Entity\SportMatch;
use App\Entity\Tournament;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(TournamentCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('PHP Sport');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Tournaments', 'fa fa-trophy', Tournament::class);
        yield MenuItem::linkToCrud('Users', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('Sport Matches', 'fa fa-futbol', SportMatch::class);
        yield MenuItem::linkToCrud('Registrations', 'fa fa-list', Registration::class);
        yield MenuItem::linkToUrl('Back to the website', 'fa fa-home', '/');
        yield MenuItem::linkToUrl('Logout', 'fa fa-sign-out', '/logout');
    }

}
