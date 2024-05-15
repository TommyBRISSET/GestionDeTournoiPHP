<?php
/**
 * PHP version 8.2.12
 *
 * @category EventListener
 * @package  App\EventListener
 * @author   Tommy Brisset <tommy.brisset@supinfo.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     src/EventListener/SportMatchStatusListener.php
 */

namespace App\EventListener;

use App\Entity\SportMatch;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class SportMatchStatusListener
{
    public function preUpdate(SportMatch $sportMatch, PreUpdateEventArgs $eventArgs): void
    {
        if ($sportMatch->getStatus() !== 'Terminé') {
            $changeSet = $eventArgs->getEntityChangeSet();

            if (isset($changeSet['scorePlayer1']) || isset($changeSet['scorePlayer2'])) {
                $scorePlayer1 = $sportMatch->getScorePlayer1();
                $scorePlayer2 = $sportMatch->getScorePlayer2();

                if ($scorePlayer1 !== null && $scorePlayer2 !== null) {
                    $sportMatch->setStatus('Terminé');
                }
            }
        }
    }

}
