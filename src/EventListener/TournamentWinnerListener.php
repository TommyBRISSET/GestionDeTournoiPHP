<?php
/**
 * PHP version 8.2.12
 *
 * @category EventListener
 * @package  App\EventListener
 * @author   Tommy Brisset <tommy.brisset@supinfo.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     src/EventListener/TournamentWinnerListener.php
 */

namespace App\EventListener;

use App\Entity\Tournament;
use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class TournamentWinnerListener
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function preUpdate(PreUpdateEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();

        if (!$entity instanceof Tournament) {
            return;
        }

        $changeSet = $eventArgs->getEntityChangeSet();

        if (isset($changeSet['winner'])) {
            $winner = $entity->getWinner();

            $this->sendWinnerEmail($entity, $winner);
        }
    }


    private function sendWinnerEmail(Tournament $tournament, User $winner): void
    {

        $email = (new Email())
            ->from('admin@symfony.com')
            ->to('test.test@gmail.com')
            ->subject('Résultats du tournoi')
            ->html(
                sprintf(
                    '<p>Le tournoi <strong>%s</strong> a été remporté par <strong>%s %s</strong>.</p>' .
                    '<p>Meilleures salutations,<br>Équipe du tournoi</p>',
                    $tournament->getTournamentName(),
                    $winner->getFirstName(),
                    $winner->getLastName()
                )
            );
        $this->mailer->send($email);
    }

}
