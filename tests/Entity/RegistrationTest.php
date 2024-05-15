<?php
/**
 * PHP version 8.2.12
 *
 * @category Tests
 * @package  App\Tests\Entity
 * @author   Tommy Brisset <tommy.brisset@supinfo.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     tests/Entity/RegistrationTest.php
 */

namespace App\Tests\Entity;

use App\Entity\Registration;
use App\Entity\Tournament;
use App\Entity\User;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class RegistrationTest extends TestCase
{
    private Registration $registration;

    /**
     * Set up the test
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->registration = new Registration();
    }

    /**
     * Test the ID
     *
     * @return void
     */
    public function testId()
    {
        $this->assertNull($this->registration->getId());
    }

    /**
     * Test the registration date
     *
     * @return void
     */
    public function testRegistrationDate()
    {
        $registrationDate = new \DateTime('2024-05-01');
        $this->registration->setRegistrationDate($registrationDate);
        $this->assertEquals($registrationDate, $this->registration->getRegistrationDate());
    }

    /**
     * Test the status
     *
     * @return void
     */
    public function testStatus()
    {
        $this->registration->setStatus('Pending');
        $this->assertEquals('Pending', $this->registration->getStatus());
    }

    /**
     * Test the player
     *
     * @return void
     * @throws Exception
     */
    public function testPlayer()
    {
        $user = $this->createMock(User::class);
        $this->registration->setPlayer($user);
        $this->assertEquals($user, $this->registration->getPlayer());
    }

    /**
     * Test the tournament
     *
     * @return void
     * @throws Exception
     */
    public function testTournament()
    {
        $tournament = $this->createMock(Tournament::class);
        $this->registration->setTournament($tournament);
        $this->assertEquals($tournament, $this->registration->getTournament());
    }
}
