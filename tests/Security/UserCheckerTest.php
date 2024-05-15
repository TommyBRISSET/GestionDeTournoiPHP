<?php
/**
 * PHP version 8.2.12
 *
 * @category Tests
 * @package  App\Tests\Security
 * @author   Tommy Brisset <tommy.brisset@supinfo.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     tests/Security/UserCheckerTest.php
 */

namespace App\Tests\Security;

use App\Entity\User;
use App\Security\UserChecker;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class UserCheckerTest extends TestCase
{
    private UserChecker $userChecker;

    /**
     * Set up the test
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->userChecker = new UserChecker();
    }

    /**
     * Provide user status
     *
     * @return array[]
     *
     * @psalm-return array{0: array{0: string, 1: null|string}}
     */
    public static function userStatusProvider(): array
    {
        return [
            ['active', null],
            ['suspendu', 'Votre compte a été suspendu. Veuillez contacter un administrateur.'],
            ['banni', 'Votre compte a été banni.'],
        ];
    }

    /**
     * Test different user statuses post auth
     *
     * @dataProvider userStatusProvider
     *
     * @param string      $status                   The user status
     * @param null|string $expectedExceptionMessage The expected exception message
     *
     * @return void
     */
    #[DataProvider('userStatusProvider')]
    public function testDifferentUserStatues(string $status, ?string $expectedExceptionMessage): void
    {
        $user = (new User())->setStatus($status);

        if ($expectedExceptionMessage !== null) {
            $this->expectException(CustomUserMessageAuthenticationException::class);
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        $this->userChecker->checkPreAuth($user);

        if ($expectedExceptionMessage === null) {
            $this->assertTrue(true);
        }
    }

    /**
     * Provide user status
     *
     * @return array[]
     *
     * @psalm-return array{0: array{0: string, 1: null|string}}
     */
    #[DataProvider('userStatusProvider')]
    public function testDifferentUserStatuesPostAuth(string $status, ?string $expectedExceptionMessage): void
    {
        $user = (new User())->setStatus($status);

        if ($expectedExceptionMessage !== null) {
            $this->expectException(CustomUserMessageAuthenticationException::class);
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        $this->userChecker->checkPostAuth($user);

        if ($expectedExceptionMessage === null) {
            $this->assertTrue(true);
        }
    }
}
