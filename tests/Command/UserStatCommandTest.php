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
 * @link                       src/Tests/Repository/RegistrationRepositoryTest.php
 */

namespace App\Tests\Command;

use App\Command\UserStatCommand;
use App\Entity\Tournament;
use App\Entity\User;
use App\Repository\TournamentRepository;
use App\Repository\UserRepository;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;


class UserStatCommandTest extends TestCase
{
    /**
     * Test the success of the UserStatCommand
     *
     * @return void
     * @throws Exception
     */
    public function testSuccessValidUserAndTournament(): void
    {
        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->method('find')->willReturn($this->createMock(User::class));

        $tournamentRepository = $this->createMock(TournamentRepository::class);
        $tournamentRepository->method('find')->willReturn($this->createMock(Tournament::class));
        $tournamentRepository->method('countWins')->willReturn(5);
        $tournamentRepository->method('countLosses')->willReturn(3);

        $command = new UserStatCommand($userRepository, $tournamentRepository);
        $application = new Application();
        $application->add($command);

        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
            'userId' => 1,
            'tournamentId' => 1,
            ]
        );

        $this->assertEquals(Command::SUCCESS, $commandTester->getStatusCode());
    }

    /**
     * Test the failure of the UserStatCommand with an invalid user
     *
     * @return void
     * @throws Exception
     */
    public function testFailureInvalidUser(): void
    {
        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->method('find')->willReturn(null);

        $tournamentRepository = $this->createMock(TournamentRepository::class);

        $command = new UserStatCommand($userRepository, $tournamentRepository);
        $application = new Application();
        $application->add($command);

        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
            'userId' => 1,
            ]
        );

        $this->assertEquals(Command::FAILURE, $commandTester->getStatusCode());
    }

    /**
     * Test the failure of the UserStatCommand with an invalid tournament
     *
     * @return void
     * @throws Exception
     */
    public function testFailureInvalidTournament(): void
    {
        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->method('find')->willReturn($this->createMock(User::class));

        $tournamentRepository = $this->createMock(TournamentRepository::class);
        $tournamentRepository->method('find')->willReturn(null);

        $command = new UserStatCommand($userRepository, $tournamentRepository);
        $application = new Application();
        $application->add($command);

        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
            'userId' => 1,
            'tournamentId' => 1,
            ]
        );

        $this->assertEquals(Command::FAILURE, $commandTester->getStatusCode());
    }
}
