<?php
/**
 * PHP version 8.2.12
 *
 * @category                 Entity
 * @package                  App\Entity
 * @Entity
 * @Table(name="tournament")
 * @author                   Tommy Brisset <tommy.brisset@supinfo.com>
 * @license                  https://opensource.org/licenses/MIT MIT License
 * @link                     src/Entity/Tournament.php
 */

namespace App\Entity;

use App\Repository\TournamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: TournamentRepository::class)]
class Tournament
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\Positive(message: 'The ID must be a positive integer')]
    #[Assert\NotBlank(message: 'The ID cannot be blank')]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\Length(
        min: 3,
        max: 100,
        minMessage: 'The tournament name must be at least 3 characters long',
        maxMessage: 'The tournament name cannot be longer than 100 characters'
    )]
    #[Assert\NotBlank(message: 'The tournament name cannot be blank')]
    private ?string $tournamentName = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(length: 100, nullable: true)]

    private ?string $location = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $maxParticipants = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $sport = null;

    #[ORM\ManyToOne(inversedBy: 'tournaments')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $organizer = null;

    #[ORM\ManyToOne(inversedBy: 'tournaments_winner')]
    private ?User $winner = null;

    /**
     * @var Collection<int, SportMatch>
     */
    #[ORM\OneToMany(targetEntity: SportMatch::class, mappedBy: 'tournament_sportMatch')]
    private Collection $sportMatchs;

    /**
     * @var Collection<int, Registration>
     */
    #[ORM\OneToMany(targetEntity: Registration::class, mappedBy: 'tournament')]
    private Collection $registrations_tournament;

    /**
     * @var Collection<int, SportMatch>
     */
    #[ORM\OneToMany(targetEntity: SportMatch::class, mappedBy: 'tournament')]
    private Collection $sportMatches_Tournament;

    public function __construct()
    {
        $this->sportMatchs = new ArrayCollection();
        $this->registrations_tournament = new ArrayCollection();
        $this->sportMatches_Tournament = new ArrayCollection();
    }

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
     * @return Tournament
     */
    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the tournament name.
     *
     * @return string|null The tournament name
     */
    public function getTournamentName(): ?string
    {
        return $this->tournamentName;
    }

    /**
     * Sets the tournament name.
     *
     * @param string $tournamentName The tournament name to set
     *
     * @return Tournament
     */
    public function setTournamentName(string $tournamentName): static
    {
        $this->tournamentName = $tournamentName;

        return $this;
    }

    /**
     * Gets the start date.
     *
     * @return \DateTimeInterface|null The start date
     */
    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    /**
     * Sets the start date.
     *
     * @param \DateTimeInterface $startDate The start date to set
     *
     * @return Tournament
     */
    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Gets the end date.
     *
     * @return \DateTimeInterface|null The end date
     */
    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    /**
     * Sets the end date.
     *
     * @param \DateTimeInterface $endDate The end date to set
     *
     * @return Tournament
     */
    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Gets the location.
     *
     * @return string|null The location
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * Sets the location.
     *
     * @param string $location The location to set
     *
     * @return Tournament
     */
    public function setLocation(?string $location): static
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Gets the description.
     *
     * @return string|null The description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Sets the description.
     *
     * @param string $description The description to set
     *
     * @return Tournament
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Gets the maximum number of participants.
     *
     * @return int|null The maximum number of participants
     */
    public function getMaxParticipants(): ?int
    {
        return $this->maxParticipants;
    }

    /**
     * Sets the maximum number of participants.
     *
     * @param int $maxParticipants The maximum number of participants to set
     *
     * @return Tournament
     */
    public function setMaxParticipants(?int $maxParticipants): static
    {
        $this->maxParticipants = $maxParticipants;

        return $this;
    }

    /**
     * Gets the status.
     *
     * @return string|null The status
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * Sets the status.
     *
     * @param string $status The status to set
     *
     * @return Tournament
     */
    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Gets the sport.
     *
     * @return string|null The sport
     */
    public function getSport(): ?string
    {
        return $this->sport;
    }

    /**
     * Sets the sport.
     *
     * @param string $sport The sport to set
     *
     * @return Tournament
     */
    public function setSport(?string $sport): static
    {
        $this->sport = $sport;

        return $this;
    }

    /**
     * Gets the organizer.
     *
     * @return User|null The organizer
     */
    public function getOrganizer(): ?User
    {
        return $this->organizer;
    }

    /**
     * Sets the organizer.
     *
     * @param User|null $organizer The organizer to set
     *
     * @return Tournament
     */
    public function setOrganizer(?User $organizer): static
    {
        $this->organizer = $organizer;

        return $this;
    }

    /**
     * Gets the winner.
     *
     * @return User|null The winner
     */
    public function getWinner(): ?User
    {
        return $this->winner;
    }

    /**
     * Sets the winner.
     *
     * @param User|null $winner The winner to set
     *
     * @return Tournament
     */
    public function setWinner(?User $winner): static
    {
        $this->winner = $winner;

        return $this;
    }

    /**
     * @return Collection<int, SportMatch>
     */
    public function getSportMatchs(): Collection
    {
        return $this->sportMatchs;
    }

    /**
     * @param Collection<int, SportMatch> $sportMatchs
     *
     * @return Tournament
     */
    public function addSportMatch(sportMatch $sportMatch): static
    {
        if (!$this->sportMatchs->contains($sportMatch)) {
            $this->sportMatchs->add($sportMatch);
            $sportMatch->setTournamentSportMatch($this);
        }

        return $this;
    }

    /**
     * @param Collection<int, SportMatch> $sportMatchs
     *
     * @return Tournament
     */
    public function removeSportMatch(sportMatch $sportMatch): static
    {
        if ($this->sportMatchs->removeElement($sportMatch)) {
            // set the owning side to null (unless already changed)
            if ($sportMatch->getTournamentSportMatch() === $this) {
                $sportMatch->setTournamentSportMatch(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Registration>
     */
    public function getRegistrationsTournament(): Collection
    {
        return $this->registrations_tournament;
    }

    /**
     * @param Collection<int, Registration> $registrationsTournament
     *
     * @return Tournament
     */
    public function addRegistrationsTournament(Registration $registrationsTournament): static
    {
        if (!$this->registrations_tournament->contains($registrationsTournament)) {
            $this->registrations_tournament->add($registrationsTournament);
            $registrationsTournament->setTournament($this);
        }

        return $this;
    }

    /**
     * @param Collection<int, Registration> $registrationsTournament
     *
     * @return Tournament
     */
    public function removeRegistrationsTournament(Registration $registrationsTournament): static
    {
        if ($this->registrations_tournament->removeElement($registrationsTournament)) {
            // set the owning side to null (unless already changed)
            if ($registrationsTournament->getTournament() === $this) {
                $registrationsTournament->setTournament(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SportMatch>
     */
    public function getSportMatchesTournament(): Collection
    {
        return $this->sportMatches_Tournament;
    }

    /**
     * @param Collection<int, SportMatch> $sportMatchesTournament
     *
     * @return Tournament
     */
    public function addSportMatchesTournament(SportMatch $sportMatchesTournament): static
    {
        if (!$this->sportMatches_Tournament->contains($sportMatchesTournament)) {
            $this->sportMatches_Tournament->add($sportMatchesTournament);
            $sportMatchesTournament->setTournament($this);
        }

        return $this;
    }

    /**
     * @param Collection<int, SportMatch> $sportMatchesTournament
     *
     * @return Tournament
     */
    public function removeSportMatchesTournament(SportMatch $sportMatchesTournament): static
    {
        if ($this->sportMatches_Tournament->removeElement($sportMatchesTournament)) {
            // set the owning side to null (unless already changed)
            if ($sportMatchesTournament->getTournament() === $this) {
                $sportMatchesTournament->setTournament(null);
            }
        }

        return $this;
    }

    /**
     * Gets the string representation of the tournament.
     *
     * @return string The string representation of the tournament
     */
    public function __toString(): string
    {
        return $this->tournamentName;
    }
}
