<?php
/**
 * PHP version 8.2.12
 *
 * @category                  Entity
 * @package                   App\Entity
 * @Entity
 * @Table(name="sport_match")
 * @author                    Tommy Brisset <tommy.brisset@supinfo.com>
 * @license                   https://opensource.org/licenses/MIT MIT License
 * @link                      src/Entity/SportMatch.php
 */

namespace App\Entity;

use App\Repository\SportMatchRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: SportMatchRepository::class)]
class SportMatch
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\Positive(message: 'The ID must be a positive integer')]
    #[Assert\NotBlank(message: 'The ID cannot be blank')]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $matchDate = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Type(type: 'integer', message: 'The score of player 1 must be an integer')]
    private ?int $scorePlayer1 = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Type(type: 'integer', message: 'The score of player 2 must be an integer')]
    private ?int $scorePlayer2 = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'sportMatchs')]
    private ?Tournament $tournament_sportMatch = null;

    #[ORM\ManyToOne(inversedBy: 'sportMatches_Tournament')]
    private ?Tournament $tournament = null;

    #[ORM\ManyToOne(inversedBy: 'sportMatches_user_p1')]
    private ?User $player1 = null;

    #[ORM\ManyToOne(inversedBy: 'sportMatches_user_p2')]
    private ?User $player2 = null;

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
     * @return SportMatch
     */
    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the match date.
     *
     * @return \DateTimeInterface|null The match date
     */
    public function getMatchDate(): ?\DateTimeInterface
    {
        return $this->matchDate;
    }

    /**
     * Sets the match date.
     *
     * @param \DateTimeInterface $matchDate The match date to set
     *
     * @return SportMatch
     */
    public function setMatchDate(\DateTimeInterface $matchDate): static
    {
        $this->matchDate = $matchDate;

        return $this;
    }

    /**
     * Gets the score of player 1.
     *
     * @return int|null The score of player 1
     */
    public function getScorePlayer1(): ?int
    {
        return $this->scorePlayer1;
    }

    /**
     * Sets the score of player 1.
     *
     * @param int $scorePlayer1 The score of player 1 to set
     *
     * @return SportMatch
     */
    public function setScorePlayer1(int $scorePlayer1): static
    {
        $this->scorePlayer1 = $scorePlayer1;

        return $this;
    }

    /**
     * Gets the score of player 2.
     *
     * @return int|null The score of player 2
     */
    public function getScorePlayer2(): ?int
    {
        return $this->scorePlayer2;
    }

    /**
     * Sets the score of player 2.
     *
     * @param int $scorePlayer2 The score of player 2 to set
     *
     * @return SportMatch
     */
    public function setScorePlayer2(int $scorePlayer2): static
    {
        $this->scorePlayer2 = $scorePlayer2;

        return $this;
    }

    /**
     * Gets the status of the sport match.
     *
     * @return string|null The status of the sport match
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * Sets the status of the sport match.
     *
     * @param string $status The status to set
     *
     * @return SportMatch
     */
    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Gets the tournament associated with the sport match.
     *
     * @return Tournament|null The tournament associated with the sport match
     */
    public function getTournamentSportMatch(): ?Tournament
    {
        return $this->tournament_sportMatch;
    }

    /**
     * Sets the tournament associated with the sport match.
     *
     * @param Tournament|null $tournament_sportMatch The tournament to set
     *
     * @return SportMatch
     */
    public function setTournamentSportMatch(?Tournament $tournament_sportMatch): static
    {
        $this->tournament_sportMatch = $tournament_sportMatch;

        return $this;
    }

    /**
     * Gets the tournament associated with the sport match.
     *
     * @return Tournament|null The tournament associated with the sport match
     */
    public function getTournament(): ?Tournament
    {
        return $this->tournament;
    }

    /**
     * Sets the tournament associated with the sport match.
     *
     * @param Tournament|null $tournament The tournament to set
     *
     * @return SportMatch
     */
    public function setTournament(?Tournament $tournament): static
    {
        $this->tournament = $tournament;

        return $this;
    }

    /**
     * Gets the player 1 associated with the sport match.
     *
     * @return User|null The player 1 associated with the sport match
     */
    public function getPlayer1(): ?User
    {
        return $this->player1;
    }

    /**
     * Sets the player 1 associated with the sport match.
     *
     * @param User|null $player1 The player 1 to set
     *
     * @return SportMatch
     */
    public function setPlayer1(?User $player1): static
    {
        $this->player1 = $player1;

        return $this;
    }

    /**
     * Gets the player 2 associated with the sport match.
     *
     * @return User|null The player 2 associated with the sport match
     */
    public function getPlayer2(): ?User
    {
        return $this->player2;
    }

    /**
     * Sets the player 2 associated with the sport match.
     *
     * @param User|null $player2 The player 2 to set
     *
     * @return SportMatch
     */
    public function setPlayer2(?User $player2): static
    {
        $this->player2 = $player2;

        return $this;
    }
}
