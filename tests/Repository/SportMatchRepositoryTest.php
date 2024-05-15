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
 * @link                       tests/Repository/RegistrationRepositoryTest.php
 */

namespace App\Tests\Repository;

use App\Entity\SportMatch;
use App\Repository\SportMatchRepository;
use PHPUnit\Framework\TestCase;

class SportMatchRepositoryTest extends TestCase
{
    /**
     * Test if the sport match is found by its id
     *
     * @return void
     */
    public function testFindsSportMatchById(): void
    {
        $sportMatch = $this->createMock(SportMatch::class);

        $repository = $this->createMock(SportMatchRepository::class);
        $repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($sportMatch);

        $this->assertSame($sportMatch, $repository->find(1));
    }

    /**
     * Test if no sport match is found by its id
     *
     * @return void
     */
    public function testNoSportMatchFound(): void
    {
        $repository = $this->createMock(SportMatchRepository::class);
        $repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->assertNull($repository->find(1));
    }

    /**
     * Test if the sport match is found by its name
     *
     * @return void
     */
    public function testFindsSportMatchCriteria(): void
    {
        $sportMatch = $this->createMock(SportMatch::class);

        $repository = $this->createMock(SportMatchRepository::class);
        $repository->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => 'Match 1'])
            ->willReturn($sportMatch);

        $this->assertSame($sportMatch, $repository->findOneBy(['name' => 'Match 1']));
    }

    /**
     * Test if no sport match matches the criteria
     *
     * @return void
     */
    public function testNoSportMatchMatchesCriteria(): void
    {
        $repository = $this->createMock(SportMatchRepository::class);
        $repository->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => 'Match 1'])
            ->willReturn(null);

        $this->assertNull($repository->findOneBy(['name' => 'Match 1']));
    }
}
