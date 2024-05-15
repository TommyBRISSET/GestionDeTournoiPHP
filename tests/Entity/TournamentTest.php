<?php
/**
 * PHP version 8.2.12
 *
 * @category Tests
 * @package  App\Tests\Entity
 * @author   Tommy Brisset <tommy.brisset@supinfo.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     tests/Entity/TournamentTest.php
 */

namespace App\Tests\Entity;

use App\Entity\Tournament;
use PHPUnit\Framework\TestCase;

class TournamentTest extends TestCase
{
    private Tournament $tournament;

    /**
     * Set up the test
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->tournament = new Tournament();
    }

    /**
     * Test the id
     *
     * @return void
     */
    public function testId()
    {
        $this->assertNull($this->tournament->getId());
    }

    /**
     * Test the tournament name
     *
     * @return void
     */
    public function testTournamentName()
    {
        $this->tournament->setTournamentName('Test Tournament');
        $this->assertEquals('Test Tournament', $this->tournament->getTournamentName());
    }

    /**
     * Test the start date
     *
     * @return void
     */
    public function testStartDate()
    {
        $startDate = new \DateTime('2024-05-01');
        $this->tournament->setStartDate($startDate);
        $this->assertEquals($startDate, $this->tournament->getStartDate());
    }

    /**
     * Test the end date
     *
     * @return void
     */
    public function testEndDate()
    {
        $endDate = new \DateTime('2024-05-10');
        $this->tournament->setEndDate($endDate);
        $this->assertEquals($endDate, $this->tournament->getEndDate());
    }

    /**
     * Test the location
     *
     * @return void
     */
    public function testLocation()
    {
        $this->tournament->setLocation('Test Location');
        $this->assertEquals('Test Location', $this->tournament->getLocation());
    }

    /**
     * Test the description
     *
     * @return void
     */
    public function testDescription()
    {
        $this->tournament->setDescription('Test Description');
        $this->assertEquals('Test Description', $this->tournament->getDescription());
    }

    /**
     * Test the max participants
     *
     * @return void
     */
    public function testMaxParticipants()
    {
        $this->tournament->setMaxParticipants(50);
        $this->assertEquals(50, $this->tournament->getMaxParticipants());
    }

    /**
     * Test the status
     *
     * @return void
     */
    public function testStatus()
    {
        $this->tournament->setStatus('Active');
        $this->assertEquals('Active', $this->tournament->getStatus());
    }

    /**
     * Test the sport
     *
     * @return void
     */
    public function testSport()
    {
        $this->tournament->setSport('Football');
        $this->assertEquals('Football', $this->tournament->getSport());
    }
}
