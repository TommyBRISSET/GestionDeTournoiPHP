<?php
/**
 * PHP version 8.2.12
 *
 * @category Tests
 * @package  App\Tests\EventListener
 * @author   Tommy Brisset <tommy.brisset@supinfo.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     src/Tests/EventListener/SportMatchStatusListenerTest.php
 */

namespace App\Tests\EventListener;

use App\Entity\SportMatch;
use App\EventListener\SportMatchStatusListener;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class SportMatchStatusListenerTest extends TestCase
{
    private $listener;

    /**
     * Set up the test
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->listener = new SportMatchStatusListener();
    }

    /**
     * Test the update status
     *
     * @return void
     */
    public function testUpdateStatus(): void
    {
        $sportMatch = new SportMatch();
        $sportMatch->setScorePlayer1(3);
        $sportMatch->setScorePlayer2(2);
        $sportMatch->setStatus('En cours');

        $changeSet = [
            'scorePlayer1' => [null, 3],
            'scorePlayer2' => [null, 2],
        ];

        $eventArgs = $this->getMockBuilder(PreUpdateEventArgs::class)
            ->disableOriginalConstructor()
            ->getMock();

        $eventArgs->expects($this->once())
            ->method('getEntityChangeSet')
            ->willReturn($changeSet);

        $this->listener->preUpdate($sportMatch, $eventArgs);

        $this->assertEquals('Terminé', $sportMatch->getStatus());
    }

    /**
     * Test the status already terminated
     *
     * @return void
     * @throws Exception
     */
    public function testStatusAlreadyTerminated(): void
    {
        $sportMatch = new SportMatch();
        $sportMatch->setScorePlayer1(3);
        $sportMatch->setScorePlayer2(2);
        $sportMatch->setStatus('Terminé');

        $changeSet = [
            'scorePlayer1' => [null, 3],
            'scorePlayer2' => [null, 2],
        ];

        $eventArgs = $this->createMock(PreUpdateEventArgs::class);
        $eventArgs->expects($this->never())
            ->method('getEntityChangeSet');

        $this->listener->preUpdate($sportMatch, $eventArgs);

        $this->assertEquals('Terminé', $sportMatch->getStatus());
    }


    /**
     * Test the status not changed
     *
     * @return void
     * @throws Exception
     */
    public function testStatusNotChanged(): void
    {
        $sportMatch = new SportMatch();
        $sportMatch->setScorePlayer1(3);
        $sportMatch->setStatus('en cours');

        $changeSet = [
            'scorePlayer1' => [null, 3],
        ];

        $eventArgs = $this->createMock(PreUpdateEventArgs::class);
        $eventArgs->expects($this->once())
            ->method('getEntityChangeSet')
            ->willReturn($changeSet);

        $this->listener->preUpdate($sportMatch, $eventArgs);

        $this->assertEquals('en cours', $sportMatch->getStatus());
    }
}
