<?php
/**
 * PHP version 8.2.12
 *
 * @category                   Entity
 * @package                    App\Entity
 * @Entity
 * @Table(name="registration")
 * @author                     Tommy Brisset <tommy.brisset@supinfo.com>
 * @license                    https://opensource.org/licenses/MIT MIT License
 * @link                       src/Entity/Registration.php
 */

namespace App\Entity;

use App\Repository\RegistrationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RegistrationRepository::class)]
class Registration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\Positive(message: 'The ID must be a positive integer')]
    #[Assert\NotBlank(message: 'The ID cannot be blank')]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'The registration date cannot be blank')]
    private ?\DateTimeInterface $registrationDate = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'registrations_user')]
    private ?User $player = null;

    #[ORM\ManyToOne(inversedBy: 'registrations_tournament')]
    private ?Tournament $tournament = null;

    /**
     * Gets the id.
     *
     * @return int|null The id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the id.
     *
     * @param int $id The id to set
     *
     * @return Registration
     */
    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the registration date.
     *
     * @return \DateTimeInterface|null The registration date
     */
    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registrationDate;
    }

    /**
     * Sets the registration date.
     *
     * @param \DateTimeInterface $registrationDate The registration date to set
     *
     * @return Registration
     */
    public function setRegistrationDate(\DateTimeInterface $registrationDate): static
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    /**
     * Gets the status of the registration.
     *
     * @return string|null The status of the registration
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * Sets the status of the registration.
     *
     * @param string $status The status to set
     *
     * @return Registration
     */
    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Gets the player associated with the registration.
     *
     * @return User|null The player associated with the registration
     */
    public function getPlayer(): ?User
    {
        return $this->player;
    }

    /**
     * Sets the player associated with the registration.
     *
     * @param User|null $player The player to set
     *
     * @return Registration
     */
    public function setPlayer(?User $player): static
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Gets the tournament associated with the registration.
     *
     * @return Tournament|null The tournament associated with the registration
     */
    public function getTournament(): ?Tournament
    {
        return $this->tournament;
    }

    /**
     * Sets the tournament associated with the registration.
     *
     * @param Tournament|null $tournament The tournament to set
     *
     * @return Registration
     */
    public function setTournament(?Tournament $tournament): static
    {
        $this->tournament = $tournament;

        return $this;
    }

}
