<?php
/**
 * PHP version 8.2.12
 *
 * @category EventListener
 * @package  App\EventListener
 * @author   Tommy Brisset <tommy.brisset@supinfo.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     src/EventListener/SportMatchScoreListener.php
 */

namespace App\EventListener;

use App\Entity\SportMatch;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\Event\PreUpdateEventArgs;


class SportMatchScoreListener
{
    private MailerInterface $mailer;
    private UserRepository $userRepository;
    private Security $security;


    public function __construct(MailerInterface $mailer, UserRepository $userRepository, $security)
    {
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
        $this->security = $security;

    }

    public function preUpdate(PreUpdateEventArgs $eventArgs): void
    {

        $sportMatch = $eventArgs->getObject();

        if (!$sportMatch instanceof SportMatch) {
            return;
        }

        $user = $this->security->getUser();

        if ($user && $this->security->isGranted('ROLE_ADMIN')) {
            return;
        }

        $sportMatch = $eventArgs->getObject();

        if (!$sportMatch instanceof SportMatch) {
            return;
        }

        $changeSet = $eventArgs->getEntityChangeSet();

        if (isset($changeSet['scorePlayer1']) && $sportMatch->getScorePlayer1() !== null) {
            if (!isset($changeSet['scorePlayer2']) || $sportMatch->getScorePlayer2() === null) {
                $this->sendEmailToPlayer($sportMatch->getPlayer2()->getId(), $sportMatch->getPlayer1()->getFirstName());
            }
        }

        if (isset($changeSet['scorePlayer2']) && $sportMatch->getScorePlayer2() !== null) {
            if (!isset($changeSet['scorePlayer1']) || $sportMatch->getScorePlayer1() === null) {
                $this->sendEmailToPlayer($sportMatch->getPlayer1()->getId(), $sportMatch->getPlayer2()->getFirstName());
            }
        }
    }

    private function sendEmailToPlayer(int $playerId, string $otherPlayerName): void
    {
        $user = $this->userRepository->find($playerId);
        if (!$user) {
            return;
        }

        $playerEmail = $user->getEmailAddress();

        $email = (new Email())
            ->from('admin@symfony.com')
            ->to($playerEmail)
            ->subject('Demande de mise à jour du score')
            ->html(
                sprintf(
                    '<p>Merci de mettre à jour le score de votre match contre <strong>%s</strong>.</p>' .
                    '<p>Meilleures salutations,<br>Équipe du tournoi</p>',
                    $otherPlayerName
                )
            );

        $this->mailer->send($email);
    }
}
