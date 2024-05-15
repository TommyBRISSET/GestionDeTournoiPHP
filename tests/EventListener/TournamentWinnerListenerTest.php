<?php
/**
 * PHP version 8.2.12
 *
 * @category Tests
 * @package  App\Tests\EventListener
 * @author   Tommy Brisset <tommy.brisset@supinfo.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     src/Tests/EventListener/TournamentWinnerListenerTest.php
 */

namespace App\Tests\EventListener;

use App\Entity\Tournament;
use App\Entity\User;
use App\EventListener\TournamentWinnerListener;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;

class TournamentWinnerListenerTest extends TestCase
{
    private $mailer;
    private $listener;

    protected function setUp(): void
    {
        $this->mailer = $this->createMock(MailerInterface::class);
        $this->listener = new TournamentWinnerListener($this->mailer);
    }

    public function testUpdateNoTournamentEntity(): void
    {
        $eventArgs = $this->createMock(PreUpdateEventArgs::class);
        $eventArgs->method('getObject')->willReturn(new \stdClass());

        $this->listener->preUpdate($eventArgs);

        $this->assertTrue(true);
    }

    public function testNoWinnerChange(): void
    {
        $eventArgs = $this->createMock(PreUpdateEventArgs::class);
        $eventArgs->method('getObject')->willReturn(new Tournament());
        $eventArgs->method('getEntityChangeSet')->willReturn([]);

        $this->listener->preUpdate($eventArgs);

        $this->assertTrue(true);
    }

    public function testWinnerChange(): void
    {
        $winner = $this->createMock(User::class);
        $winner->method('getFirstName')->willReturn('John');
        $winner->method('getLastName')->willReturn('Doe');

        $tournament = $this->createMock(Tournament::class);
        $tournament->method('getWinner')->willReturn($winner);
        $tournament->method('getTournamentName')->willReturn('Test Tournament');

        $eventArgs = $this->createMock(PreUpdateEventArgs::class);
        $eventArgs->method('getObject')->willReturn($tournament);
        $eventArgs->method('getEntityChangeSet')->willReturn(['winner' => [null, $winner]]);

        $this->mailer->expects($this->once())->method('send');

        $this->listener->preUpdate($eventArgs);
    }
}