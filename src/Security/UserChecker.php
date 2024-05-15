<?php
/**
 * PHP version 8.2.12
 *
 * @category Security
 * @package  App\Security
 * @author   Tommy Brisset <tommy.brisset@supinfo.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     src/Security/UserChecker.php
 */

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{

    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if ($user->getStatus() === 'suspendu') {
            throw new CustomUserMessageAuthenticationException('Votre compte a été suspendu. Veuillez contacter un administrateur.');
        }
        if ($user->getStatus() === 'banni') {
            throw new CustomUserMessageAuthenticationException('Votre compte a été banni.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        $this->checkPreAuth($user);
    }
}
