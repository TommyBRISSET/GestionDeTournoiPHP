<?php
/**
 * PHP version 8.2.12
 *
 * @category Tests
 * @package  App\Tests\Entity
 * @author   Tommy Brisset <tommy.brisset@supinfo.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     tests/Entity/SportMatchTest.php
 */

namespace App\Tests\Entity;

use App\Entity\SportMatch;
use App\Entity\Tournament;
use App\Entity\User;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class SportMatchTest extends TestCase
{
    private SportMatch $sportMatch;

    /**
     * Set up the test
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->sportMatch = new SportMatch();
    }

    /**
     * Test the ID
     *
     * @return void
     */
    public function testId()
    {
        $this->assertNull($this->sportMatch->getId());
    }

    /**
     * Test the match date
     *
     * @return void
     */
    public function testMatchDate()
    {
        $matchDate = new \DateTime('2024-05-01');
        $this->sportMatch->setMatchDate($matchDate);
        $this->assertEquals($matchDate, $this->sportMatch->getMatchDate());
    }

    /**
     * Test the score of player 1
     *
     * @return void
     */
    public function testScorePlayer1()
    {
        $this->sportMatch->setScorePlayer1(5);
        $this->assertEquals(5, $this->sportMatch->getScorePlayer1());
    }

    /**
     * Test the score of player 2
     *
     * @return void
     */
    public function testScorePlayer2()
    {
        $this->sportMatch->setScorePlayer2(3);
        $this->assertEquals(3, $this->sportMatch->getScorePlayer2());
    }

    /**
     * Test the status
     *
     * @return void
     */
    public function testStatus()
    {
        $this->sportMatch->setStatus('Finished');
        $this->assertEquals('Finished', $this->sportMatch->getStatus());
    }

    /**
     * Test the tournament sport match
     *
     * @return void
     * @throws Exception
     */
    public function testTournamentSportMatch()
    {
        $tournament = $this->createMock(Tournament::class);
        $this->sportMatch->setTournamentSportMatch($tournament);
        $this->assertEquals($tournament, $this->sportMatch->getTournamentSportMatch());
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
        $this->sportMatch->setTournament($tournament);
        $this->assertEquals($tournament, $this->sportMatch->getTournament());
    }

    /**
     * Test the player 1
     *
     * @return void
     * @throws Exception
     */
    public function testPlayer1()
    {
        $user = $this->createMock(User::class);
        $this->sportMatch->setPlayer1($user);
        $this->assertEquals($user, $this->sportMatch->getPlayer1());
    }

    /**
     * Test the player 2
     *
     * @return void
     * @throws Exception
     */
    public function testPlayer2()
    {
        $user = $this->createMock(User::class);
        $this->sportMatch->setPlayer2($user);
        $this->assertEquals($user, $this->sportMatch->getPlayer2());
    }
}

