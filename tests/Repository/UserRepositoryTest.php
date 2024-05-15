<?php
/**
 * PHP version 8.2.12
 *
 * @category                   Tests
 * @package                    App\Tests\Repository
 * @Entity
 * @Table(name="registration")
 * @author                     Tommy Brisset <tommy.brisset@supinfo.com>
 * @license                    https://opensource.org/licenses/MIT MIT License
 * @link                       tests/Repository/UserRepositoryTest.php
 */

namespace App\Tests\Repository;

use App\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class UserRepositoryTest extends TestCase
{
    /**
     * Test if the user is found by its id
     *
     * @return void
     */
    public function testUpgradePasswordUnsupportedUserInstance(): void
    {
        $this->expectException(UnsupportedUserException::class);

        $user = $this->createMock(PasswordAuthenticatedUserInterface::class);

        $registry = $this->createMock(ManagerRegistry::class);

        $repository = new UserRepository($registry);
        $repository->upgradePassword($user, 'newHashedPassword');
    }
}
