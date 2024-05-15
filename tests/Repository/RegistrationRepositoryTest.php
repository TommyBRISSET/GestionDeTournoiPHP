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

use App\Entity\Registration;
use App\Repository\RegistrationRepository;
use PHPUnit\Framework\TestCase;

class RegistrationRepositoryTest extends TestCase
{
    /**
     * Test if the registration is found by its id
     *
     * @return void
     */
    public function testFindsRegistrationById(): void
    {
        $registration = $this->createMock(Registration::class);

        $repository = $this->createMock(RegistrationRepository::class);
        $repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($registration);

        $this->assertSame($registration, $repository->find(1));
    }

    /**
     * Test if no registration is found by its id
     *
     * @return void
     */
    public function testNoRegistrationFound(): void
    {
        $repository = $this->createMock(RegistrationRepository::class);
        $repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->assertNull($repository->find(1));
    }

    /**
     * Test if the registration is found by its name
     *
     * @return void
     */
    public function testFindsRegistrationCriteria(): void
    {
        $registration = $this->createMock(Registration::class);

        $repository = $this->createMock(RegistrationRepository::class);
        $repository->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => 'Registration 1'])
            ->willReturn($registration);

        $this->assertSame($registration, $repository->findOneBy(['name' => 'Registration 1']));
    }

    /**
     * Test if no registration is found by its name
     *
     * @return void
     */
    public function testNoRegistrationMatchesCriteria(): void
    {
        $repository = $this->createMock(RegistrationRepository::class);
        $repository->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => 'Registration 1'])
            ->willReturn(null);

        $this->assertNull($repository->findOneBy(['name' => 'Registration 1']));
    }
}
