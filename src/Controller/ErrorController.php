<?php
/**
 * PHP version 8.2.12
 *
 * @category Controller
 * @package  App\Controller
 * @author   Tommy Brisset <tommy.brisset@supinfo.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     src/Controller/ErrorController.php
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ErrorController extends AbstractController
{
    public function accessDenied(): Response
    {
        return $this->render('error/access_denied.html.twig');
    }
}
