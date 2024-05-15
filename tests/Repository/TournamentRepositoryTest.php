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
 * @link                       tests/Repository/TournamentRepositoryTest.php
 */

namespace App\Tests\Repository;

use App\Repository\TournamentRepository;
use PHPUnit\Framework\TestCase;

class TournamentRepositoryTest extends TestCase
{
    /**
     * Test if the tournament is found by its id
     *
     * @return void
     */
    public function testCountWins(): void
    {
        $userId = 1;
        $tournamentId = 2;

        $repository = $this->createMock(TournamentRepository::class);
        $repository->expects($this->once())
            ->method('countWins')
            ->with($userId, $tournamentId)
            ->willReturn(3);

        $this->assertEquals(3, $repository->countWins($userId, $tournamentId));
    }

    /**
     * Test if the tournament is found by its id
     *
     * @return void
     */
    public function testCountLosses(): void
    {
        $userId = 1;
        $tournamentId = 2;

        $repository = $this->createMock(TournamentRepository::class);
        $repository->expects($this->once())
            ->method('countLosses')
            ->with($userId, $tournamentId)
            ->willReturn(2);

        $this->assertEquals(2, $repository->countLosses($userId, $tournamentId));
    }

    /**
     * Test if no tournament is found by its id
     *
     * @return void
     */
    public function testReturnsNoWins(): void
    {
        $userId = 1;
        $tournamentId = 2;

        $repository = $this->createMock(TournamentRepository::class);
        $repository->expects($this->once())
            ->method('countWins')
            ->with($userId, $tournamentId)
            ->willReturn(0);

        $this->assertEquals(0, $repository->countWins($userId, $tournamentId));
    }

    /**
     * Test if no tournament is found by its id
     *
     * @return void
     */
    public function testCountLossesNoLosses(): void
    {
        $userId = 1;
        $tournamentId = 2;

        $repository = $this->createMock(TournamentRepository::class);
        $repository->expects($this->once())
            ->method('countLosses')
            ->with($userId, $tournamentId)
            ->willReturn(0);

        $this->assertEquals(0, $repository->countLosses($userId, $tournamentId));
    }
}
