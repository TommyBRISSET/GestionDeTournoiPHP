<?php
/**
 * PHP version 8.2.12
 *
 * @category DataFixtures
 * @package  App\DataFixtures
 * @author   Tommy Brisset <tommy.brisset@supinfo.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     src/DataFixtures/AppFixtures.php
 */

namespace App\DataFixtures;

use App\Entity\Registration;
use App\Entity\SportMatch;
use App\Entity\Tournament;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Create a user
        $user = new User();
        $user->SetLastName('Doe');
        $user->SetFirstName('John');
        $user->SetUsername('johndoe');
        $user->SetEmailAddress('john.doe@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $user->setStatus('actif');
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                'password'
            )
        );
        $manager->persist($user);

        // Create second user
        $user2 = new User();
        $user2->SetLastName('Leroy');
        $user2->SetFirstName('Merlin');
        $user2->SetUsername('merlinleroy');
        $user2->SetEmailAddress('merlin.leroy@gmail.com');
        $user2->setRoles(['ROLE_USER']);
        $user2->setStatus('actif');
        $user2->setPassword(
            $this->passwordHasher->hashPassword(
                $user2,
                'password'
            )
        );
        $manager->persist($user2);

        // Create third user
        $user3 = new User();
        $user3->SetLastName('Martin');
        $user3->SetFirstName('Sophie');
        $user3->SetUsername('sophiemartin');
        $user3->SetEmailAddress('sophie.martin@gmail.com');
        $user3->setRoles(['ROLE_USER']);
        $user3->setStatus('actif');
        $user3->setPassword(
            $this->passwordHasher->hashPassword(
                $user3,
                'password'
            )
        );
        $manager->persist($user3);

        // Create fourth user
        $user4 = new User();
        $user4->SetLastName('Dubois');
        $user4->SetFirstName('Luc');
        $user4->SetUsername('lucdubois');
        $user4->SetEmailAddress('luc.dubois@gmail.com');
        $user4->setRoles(['ROLE_USER']);
        $user4->setStatus('suspendu');
        $user4->setPassword(
            $this->passwordHasher->hashPassword(
                $user4,
                'password'
            )
        );
        $manager->persist($user4);

        // Create fifth user
        $user5 = new User();
        $user5->SetLastName('Roussel');
        $user5->SetFirstName('Emma');
        $user5->SetUsername('emmaroussel');
        $user5->SetEmailAddress('emma.roussel@gmail.com');
        $user5->setRoles(['ROLE_USER']);
        $user5->setStatus('banni');
        $user5->setPassword(
            $this->passwordHasher->hashPassword(
                $user5,
                'password'
            )
        );
        $manager->persist($user5);

        // Create a user with admin role
        $admin = new User();
        $admin->SetLastName('Admin');
        $admin->SetFirstName('Adminsymfony');
        $admin->SetUsername('adminsymfony');
        $admin->SetEmailAddress('admin.symfony@gmail.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setStatus('actif');
        $admin->setPassword(
            $this->passwordHasher->hashPassword(
                $admin,
                'password'
            )
        );
        $manager->persist($admin);


        // Create a tournament
        $tournament = new Tournament();
        $tournament->setTournamentName('Tournoi de tennis');
        $tournament->setStartDate(new DateTime('2023-10-01'));
        $tournament->setEndDate(new DateTime('2023-10-31'));
        $tournament->setLocation('Paris');
        $tournament->setDescription('Tournoi de tennis amateur');
        $tournament->setMaxParticipants(20);
        $today = new DateTime();
        if ($today < $tournament->getStartDate()) {
            $tournament->setStatus('À venir');
        } elseif ($today > $tournament->getEndDate()) {
            $tournament->setStatus('Terminé');
        } else {
            $tournament->setStatus('En cours');
        }
        $tournament->setSport('Tennis');
        $tournament->setOrganizer($user);
        $tournament->setWinner($user2);
        $manager->persist($tournament);

        // Create a second tournament
        $tournament2 = new Tournament();
        $tournament2->setTournamentName('Tournoi de football');
        $tournament2->setStartDate(new DateTime('2023-11-15'));
        $tournament2->setEndDate(new DateTime('2023-12-15'));
        $tournament2->setLocation('Lyon');
        $tournament2->setDescription('Tournoi de football pour les jeunes');
        $tournament2->setMaxParticipants(30);
        $today = new DateTime();
        if ($today < $tournament2->getStartDate()) {
            $tournament2->setStatus('À venir');
        } elseif ($today > $tournament2->getEndDate()) {
            $tournament2->setStatus('Terminé');
        } else {
            $tournament2->setStatus('En cours');
        }
        $tournament2->setSport('Football');
        $tournament2->setOrganizer($user3);
        $tournament2->setWinner($user4);
        $manager->persist($tournament2);

        // Create a third tournament
        $tournament3 = new Tournament();
        $tournament3->setTournamentName('Tournoi de basketball');
        $tournament3->setStartDate(new DateTime('2023-09-10'));
        $tournament3->setEndDate(new DateTime('2023-09-30'));
        $tournament3->setLocation('Marseille');
        $tournament3->setDescription('Tournoi de basketball amateur');
        $tournament3->setMaxParticipants(25);
        $today = new DateTime();
        if ($today < $tournament3->getStartDate()) {
            $tournament3->setStatus('À venir');
        } elseif ($today > $tournament3->getEndDate()) {
            $tournament3->setStatus('Terminé');
        } else {
            $tournament3->setStatus('En cours');
        }
        $tournament3->setSport('Basketball');
        $tournament3->setOrganizer($user2);
        $tournament3->setWinner($user5);
        $manager->persist($tournament3);

        // Create a fourth tournament
        $tournament4 = new Tournament();
        $tournament4->setTournamentName('Tournoi de natation');
        $tournament4->setStartDate(new DateTime('2023-07-01'));
        $tournament4->setEndDate(new DateTime('2023-07-31'));
        $tournament4->setLocation('Bordeaux');
        $tournament4->setDescription('Tournoi de natation en piscine');
        $tournament4->setMaxParticipants(40);
        $today = new DateTime();
        if ($today < $tournament4->getStartDate()) {
            $tournament4->setStatus('À venir');
        } elseif ($today > $tournament4->getEndDate()) {
            $tournament4->setStatus('Terminé');
        } else {
            $tournament4->setStatus('En cours');
        }
        $tournament4->setSport('Natation');
        $tournament4->setOrganizer($user4);
        $tournament4->setWinner($user3);
        $manager->persist($tournament4);

        // Create a fifth tournament
        $tournament5 = new Tournament();
        $tournament5->setTournamentName('Tournoi de badminton');
        $tournament5->setStartDate(new DateTime('2023-06-05'));
        $tournament5->setEndDate(new DateTime('2023-06-25'));
        $tournament5->setLocation('Toulouse');
        $tournament5->setDescription('Tournoi de badminton interclubs');
        $tournament5->setMaxParticipants(35);
        $today = new DateTime();
        if ($today < $tournament5->getStartDate()) {
            $tournament5->setStatus('À venir');
        } elseif ($today > $tournament5->getEndDate()) {
            $tournament5->setStatus('Terminé');
        } else {
            $tournament5->setStatus('En cours');
        }
        $tournament5->setSport('Badminton');
        $tournament5->setOrganizer($user5);
        $tournament5->setWinner($user2);
        $manager->persist($tournament5);

        // Create a tournament without winner
        $tournament6 = new Tournament();
        $tournament6->setTournamentName('Tournoi de golf');
        $tournament6->setStartDate(new DateTime('2023-08-01'));
        $tournament6->setEndDate(new DateTime('2023-08-31'));
        $tournament6->setLocation('Nice');
        $tournament6->setDescription('Tournoi de golf professionnel');
        $tournament6->setMaxParticipants(50);
        $today = new DateTime();
        if ($today < $tournament6->getStartDate()) {
            $tournament6->setStatus('À venir');
        } elseif ($today > $tournament6->getEndDate()) {
            $tournament6->setStatus('Terminé');
        } else {
            $tournament6->setStatus('En cours');
        }
        $tournament6->setSport('Golf');
        $tournament6->setOrganizer($user);
        $manager->persist($tournament6);


        // Create a sport match
        $sportMatch = new SportMatch();
        $sportMatch->setTournamentSportMatch($tournament);
        $sportMatch->setTournament($tournament);
        $sportMatch->setPlayer1($user);
        $sportMatch->setPlayer2($user2);
        $sportMatch->setMatchDate(new DateTime('2023-10-15'));
        $sportMatch->setScorePlayer1(6);
        $sportMatch->setScorePlayer2(4);
        $sportMatch->setStatus('Terminé');
        $manager->persist($sportMatch);

        // Create a second sport match
        $sportMatch2 = new SportMatch();
        $sportMatch2->setTournamentSportMatch($tournament2);
        $sportMatch2->setTournament($tournament2);
        $sportMatch2->setPlayer1($user3);
        $sportMatch2->setPlayer2($user4);
        $sportMatch2->setMatchDate(new DateTime('2023-11-20'));
        $sportMatch2->setScorePlayer1(2);
        $sportMatch2->setScorePlayer2(3);
        $sportMatch2->setStatus('Terminé');
        $manager->persist($sportMatch2);

        // Create a third sport match
        $sportMatch3 = new SportMatch();
        $sportMatch3->setTournamentSportMatch($tournament3);
        $sportMatch3->setTournament($tournament3);
        $sportMatch3->setPlayer1($user2);
        $sportMatch3->setPlayer2($user5);
        $sportMatch3->setMatchDate(new DateTime('2023-09-15'));
        $sportMatch3->setScorePlayer1(55);
        $sportMatch3->setScorePlayer2(50);
        $sportMatch3->setStatus('Terminé');
        $manager->persist($sportMatch3);

        // Create a fourth sport match
        $sportMatch4 = new SportMatch();
        $sportMatch4->setTournamentSportMatch($tournament4);
        $sportMatch4->setTournament($tournament4);
        $sportMatch4->setPlayer1($user4);
        $sportMatch4->setPlayer2($user3);
        $sportMatch4->setMatchDate(new DateTime('2023-07-10'));
        $sportMatch4->setScorePlayer1(100);
        $sportMatch4->setScorePlayer2(95);
        $sportMatch4->setStatus('Terminé');
        $manager->persist($sportMatch4);

        // Create a fifth sport match
        $sportMatch5 = new SportMatch();
        $sportMatch5->setTournamentSportMatch($tournament5);
        $sportMatch5->setTournament($tournament5);
        $sportMatch5->setPlayer1($user5);
        $sportMatch5->setPlayer2($user2);
        $sportMatch5->setMatchDate(new DateTime('2023-06-15'));
        $sportMatch5->setScorePlayer1(21);
        $sportMatch5->setScorePlayer2(19);
        $sportMatch5->setStatus('Terminé');
        $manager->persist($sportMatch5);

        // Create a sport match without score
        $sportMatch6 = new SportMatch();
        $sportMatch6->setTournamentSportMatch($tournament);
        $sportMatch6->setTournament($tournament);
        $sportMatch6->setPlayer1($user);
        $sportMatch6->setPlayer2($user2);
        $sportMatch6->setMatchDate(new DateTime('2023-10-20'));
        $sportMatch6->setStatus('À venir');
        $manager->persist($sportMatch6);


        // Create a registration
        $registration = new Registration();
        $registration->setTournament($tournament);
        $registration->setPlayer($user);
        $registration->setRegistrationDate(new DateTime('2023-09-01'));
        $registration->setStatus('confirmé');
        $manager->persist($registration);

        // Create a second registration
        $registration2 = new Registration();
        $registration2->setTournament($tournament2);
        $registration2->setPlayer($user3);
        $registration2->setRegistrationDate(new DateTime('2023-11-05'));
        $registration2->setStatus('confirmé');
        $manager->persist($registration2);

        // Create a third registration
        $registration3 = new Registration();
        $registration3->setTournament($tournament3);
        $registration3->setPlayer($user2);
        $registration3->setRegistrationDate(new DateTime('2023-09-05'));
        $registration3->setStatus('confirmé');
        $manager->persist($registration3);

        // Create a fourth registration
        $registration4 = new Registration();
        $registration4->setTournament($tournament4);
        $registration4->setPlayer($user4);
        $registration4->setRegistrationDate(new DateTime('2023-06-20'));
        $registration4->setStatus('confirmé');
        $manager->persist($registration4);

        // Create a fifth registration
        $registration5 = new Registration();
        $registration5->setTournament($tournament5);
        $registration5->setPlayer($user5);
        $registration5->setRegistrationDate(new DateTime('2023-06-10'));
        $registration5->setStatus('confirmé');
        $manager->persist($registration5);

        $manager->flush();
    }
}
