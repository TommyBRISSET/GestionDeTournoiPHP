<?php
/**
 * PHP version 8.2.12
 *
 * @category             Entity
 * @package              App\Entity
 * @Entity
 * @Table(name="`user`")
 * @author               Tommy Brisset <tommy.brisset@supinfo.com>
 * @license              https://opensource.org/licenses/MIT MIT License
 * @link                 src/Entity/User.php
 */

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['emailAddress'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\Positive(message: 'The id must be a positive number')]
    #[Assert\NotBlank(message: 'The id is required')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Your last name must be at least 2 characters long',
        maxMessage: 'Your last name cannot be longer than 255 characters'
    )]
    #[Assert\NotBlank(message: 'The last name is required')]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Your first name must be at least 2 characters long',
        maxMessage: 'Your first name cannot be longer than 255 characters'
    )]
    #[Assert\NotBlank(message: 'The first name is required')]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Your username must be at least 2 characters long',
        maxMessage: 'Your username cannot be longer than 255 characters'
    )]
    #[Assert\NotBlank(message: 'The username is required')]
    private ?string $username = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Your email address must be at least 2 characters long',
        maxMessage: 'Your email address cannot be longer than 255 characters'
    )]
    #[Assert\NotBlank(message: 'The email address is required')]
    #[Assert\Email(message: 'The email address is not valid')]
    private ?string $emailAddress = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Your password must be at least 2 characters long',
        maxMessage: 'Your password cannot be longer than 255 characters'
    )]
    #[Assert\NotBlank(message: 'The password is required')]
    private ?string $password = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $status = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var Collection<int, Tournament>
     */
    #[ORM\OneToMany(targetEntity: Tournament::class, mappedBy: 'organizer')]
    private Collection $tournaments;

    /**
     * @var Collection<int, Tournament>
     */
    #[ORM\OneToMany(targetEntity: Tournament::class, mappedBy: 'winner')]
    private Collection $tournaments_winner;

    /**
     * @var Collection<int, Registration>
     */
    #[ORM\OneToMany(targetEntity: Registration::class, mappedBy: 'player')]
    private Collection $registrations_user;

    /**
     * @var Collection<int, SportMatch>
     */
    #[ORM\OneToMany(targetEntity: SportMatch::class, mappedBy: 'player1')]
    private Collection $sportMatches_user_p1;

    /**
     * @var Collection<int, SportMatch>
     */
    #[ORM\OneToMany(targetEntity: SportMatch::class, mappedBy: 'player2')]
    private Collection $sportMatches_user_p2;

    public function __construct()
    {
        $this->tournaments = new ArrayCollection();
        $this->tournaments_winner = new ArrayCollection();
        $this->registrations_user = new ArrayCollection();
        $this->sportMatches_user_p1 = new ArrayCollection();
        $this->sportMatches_user_p2 = new ArrayCollection();
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
     * @return User
     */
    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Sets the id.
     *
     * @param int $id The id to set
     *
     * @return User
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Sets the last name.
     *
     * @param string $lastName The last name to set
     *
     * @return User
     */
    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Gets the first name.
     *
     * @return string|null The first name
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * Sets the first name.
     *
     * @param string $firstName The first name to set
     *
     * @return User
     */
    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Gets the username.
     *
     * @return string|null The username
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Sets the username.
     *
     * @param string $username The username to set
     *
     * @return User
     */
    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Gets the email address.
     *
     * @return string|null The email address
     */
    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    /**
     * Sets the email address.
     *
     * @param string $emailAddress The email address to set
     *
     * @return User
     */
    public function setEmailAddress(string $emailAddress): static
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    /**
     * Gets the password.
     *
     * @return string|null The password
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Sets the password.
     *
     * @param string $password The password to set
     *
     * @return User
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;

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
     * @return User
     */
    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Gets the tournaments of the user.
     *
     * @return Collection<int, Tournament> The tournaments of the user
     */
    public function getTournaments(): Collection
    {
        return $this->tournaments;
    }

    /**
     * Adds a tournament to the user.
     *
     * @param Collection<int, Tournament> $tournaments The tournament to add
     *
     * @return User The user
     */
    public function addTournament(Tournament $tournament): static
    {
        if (!$this->tournaments->contains($tournament)) {
            $this->tournaments->add($tournament);
            $tournament->setOrganizer($this);
        }

        return $this;
    }

    /**
     * Removes a tournament from the user.
     *
     * @param Collection<int, Tournament> $tournaments The tournament to remove
     *
     * @return User The user
     */
    public function removeTournament(Tournament $tournament): static
    {
        if ($this->tournaments->removeElement($tournament)) {
            // set the owning side to null (unless already changed)
            if ($tournament->getOrganizer() === $this) {
                $tournament->setOrganizer(null);
            }
        }

        return $this;
    }

    /**
     * Gets the tournaments won by the user.
     *
     * @return Collection<int, Tournament> The tournaments won by the user
     */
    public function getTournamentsWinner(): Collection
    {
        return $this->tournaments_winner;
    }

    /**
     * Adds a tournament to the user.
     *
     * @param Collection<int, Tournament> $tournamentsWinner The tournament to add
     *
     * @return User The user
     */
    public function addTournamentsWinner(Tournament $tournamentsWinner): static
    {
        if (!$this->tournaments_winner->contains($tournamentsWinner)) {
            $this->tournaments_winner->add($tournamentsWinner);
            $tournamentsWinner->setWinner($this);
        }

        return $this;
    }

    /**
     * Removes a tournament from the user.
     *
     * @param Collection<int, Tournament> $tournamentsWinner The tournament to remove
     *
     * @return User The user
     */
    public function removeTournamentsWinner(Tournament $tournamentsWinner): static
    {
        if ($this->tournaments_winner->removeElement($tournamentsWinner)) {
            // set the owning side to null (unless already changed)
            if ($tournamentsWinner->getWinner() === $this) {
                $tournamentsWinner->setWinner(null);
            }
        }

        return $this;
    }

    /**
     * Gets the registrations of the user.
     *
     * @return Collection<int, Registration> The registrations of the user
     */
    public function getRegistrationsUser(): Collection
    {
        return $this->registrations_user;
    }

    /**
     * Adds a registration to the user.
     *
     * @param Collection<int, Registration> $registrationsUser The registration to add
     *
     * @return User The user
     */
    public function addRegistrationsUser(Registration $registrationsUser): static
    {
        if (!$this->registrations_user->contains($registrationsUser)) {
            $this->registrations_user->add($registrationsUser);
            $registrationsUser->setPlayer($this);
        }

        return $this;
    }

    /**
     * Removes a registration from the user.
     *
     * @param Collection<int, Registration> $registrationsUser The registration to remove
     *
     * @return User The user
     */
    public function removeRegistrationsUser(Registration $registrationsUser): static
    {
        if ($this->registrations_user->removeElement($registrationsUser)) {
            // set the owning side to null (unless already changed)
            if ($registrationsUser->getPlayer() === $this) {
                $registrationsUser->setPlayer(null);
            }
        }

        return $this;
    }

    /**
     * Gets the sport matches of the user.
     *
     * @return Collection<int, SportMatch> The sport matches of the user
     */
    public function getSportMatchesUserP1(): Collection
    {
        return $this->sportMatches_user_p1;
    }

    /**
     * Adds a sport match to the user.
     *
     * @param Collection<int, SportMatch> $sportMatchesUserP1 The sport match to add
     *
     * @return User The user
     */
    public function addSportMatchesUserP1(SportMatch $sportMatchesUserP1): static
    {
        if (!$this->sportMatches_user_p1->contains($sportMatchesUserP1)) {
            $this->sportMatches_user_p1->add($sportMatchesUserP1);
            $sportMatchesUserP1->setPlayer1($this);
        }

        return $this;
    }

    /**
     * Removes a sport match from the user.
     *
     * @param Collection<int, SportMatch> $sportMatchesUserP1 The sport match to remove
     *
     * @return User The user
     */
    public function removeSportMatchesUserP1(SportMatch $sportMatchesUserP1): static
    {
        if ($this->sportMatches_user_p1->removeElement($sportMatchesUserP1)) {
            // set the owning side to null (unless already changed)
            if ($sportMatchesUserP1->getPlayer1() === $this) {
                $sportMatchesUserP1->setPlayer1(null);
            }
        }

        return $this;
    }

    /**
     * Gets the sport matches of the user.
     *
     * @return Collection<int, SportMatch> The sport matches of the user
     */
    public function getSportMatchesUserP2(): Collection
    {
        return $this->sportMatches_user_p2;
    }

    /**
     * Adds a sport match to the user.
     *
     * @param Collection<int, SportMatch> $sportMatchesUserP2 The sport match to add
     *
     * @return User The user
     */
    public function addSportMatchesUserP2(SportMatch $sportMatchesUserP2): static
    {
        if (!$this->sportMatches_user_p2->contains($sportMatchesUserP2)) {
            $this->sportMatches_user_p2->add($sportMatchesUserP2);
            $sportMatchesUserP2->setPlayer2($this);
        }

        return $this;
    }

    /**
     * Removes a sport match from the user.
     *
     * @param Collection<int, SportMatch> $sportMatchesUserP2 The sport match to remove
     *
     * @return User The user
     */
    public function removeSportMatchesUserP2(SportMatch $sportMatchesUserP2): static
    {
        if ($this->sportMatches_user_p2->removeElement($sportMatchesUserP2)) {
            // set the owning side to null (unless already changed)
            if ($sportMatchesUserP2->getPlayer2() === $this) {
                $sportMatchesUserP2->setPlayer2(null);
            }
        }

        return $this;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Gets the roles.
     *
     * @return array The roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Erase the user credentials.
     *
     * @return void
     */
    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * Gets the user identifier.
     *
     * @return string The user identifier
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * Gets the string representation of the user.
     *
     * @return string The string representation of the user
     */
    public function __toString(): string
    {
        return $this->getUsername() ?? 'Utilisateur inconnu';
    }
}
