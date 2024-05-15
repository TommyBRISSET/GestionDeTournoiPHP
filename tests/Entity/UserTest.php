<?php
/**
 * PHP version 8.2.12
 *
 * @category Tests
 * @package  App\Tests\Entity
 * @author   Tommy Brisset <tommy.brisset@supinfo.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     tests/Entity/UserTest.php
 */

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private User $user;

    /**
     * Set up the test
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->user = new User();
    }

    /**
     * Test the id
     *
     * @return void
     */
    public function testId()
    {
        $this->assertNull($this->user->getId());
    }

    /**
     * Test the last name
     *
     * @return void
     */
    public function testLastName()
    {
        $this->user->setLastName('Doe');
        $this->assertEquals('Doe', $this->user->getLastName());
    }

    /**
     * Test the first name
     *
     * @return void
     */
    public function testFirstName()
    {
        $this->user->setFirstName('John');
        $this->assertEquals('John', $this->user->getFirstName());
    }

    /**
     * Test the username
     *
     * @return void
     */
    public function testUsername()
    {
        $this->user->setUsername('johndoe');
        $this->assertEquals('johndoe', $this->user->getUsername());
    }

    /**
     * Test the email address
     *
     * @return void
     */
    public function testEmailAddress()
    {
        $this->user->setEmailAddress('john.doe@example.com');
        $this->assertEquals('john.doe@example.com', $this->user->getEmailAddress());
    }

    /**
     * Test the status
     *
     * @return void
     */
    public function testPassword()
    {
        $this->user->setPassword('password123');
        $this->assertEquals('password123', $this->user->getPassword());
    }

    /**
     * Test the status
     *
     * @return void
     */
    public function testRoles()
    {
        $this->user->setRoles(['ROLE_ADMIN']);
        $this->assertContains('ROLE_USER', $this->user->getRoles());
        $this->assertContains('ROLE_ADMIN', $this->user->getRoles());
    }
}
