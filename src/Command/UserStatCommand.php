<?php
/**
 * PHP version 8.2.12
 *
 * @category Command
 * @package  App\Command
 * @author   Tommy Brisset <tommy.brisset@supinfo.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     src/Command/UserStatCommand.php
 */

namespace App\Command;

use App\Repository\TournamentRepository;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'UserStatCommand',
    description: 'Give the user stats',
    aliases: ['app:user:stat'],
    hidden: false,
)]
class UserStatCommand extends Command
{
    private $userRepository;
    private $tournamentRepository;

    public function __construct(UserRepository $userRepository, tournamentRepository $tournamentRepository)
    {

        $this->userRepository = $userRepository;
        $this->tournamentRepository = $tournamentRepository;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Affiche le nombre de victoires et de défaites pour un utilisateur')
            ->addArgument('userId', InputArgument::REQUIRED, 'ID de l\'utilisateur')
            ->addArgument('tournamentId', InputArgument::OPTIONAL, 'ID du tournoi');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $userId = $input->getArgument('userId');
        $tournamentId = $input->getArgument('tournamentId');

        $user = $this->userRepository->find($userId);

        $io = new SymfonyStyle($input, $output);

        if (!$user) {
            $io->warning('Utilisateur non trouvé');
            return Command::FAILURE;
        }

        if ($tournamentId) {
            $tournament = $this->tournamentRepository->find($tournamentId);
            if (!$tournament) {
                $io->warning('Tournoi non trouvé');
                return Command::FAILURE;
            }
        }

        $wins = $this->tournamentRepository->countWins($userId, $tournamentId);
        $losses = $this->tournamentRepository->countLosses($userId, $tournamentId);


        $io->title('Statistiques de l\'utilisateur');

        $io->text(sprintf('Nom : %s', $user->getFirstName()));
        $io->text(sprintf('Prénom : %s', $user->getLastName()));
        $io->text(sprintf('username : %s', $user->getUsername()));
        if ($tournamentId) {
            $tournament = $this->tournamentRepository->find($tournamentId);
            $io->text(sprintf('Tournoi : %s', $tournament->getTournamentName()));
        }

        $io->table(
            ['Victoires', 'Défaites'],
            [[$wins, $losses]]
        );

        return Command::SUCCESS;
    }
}
