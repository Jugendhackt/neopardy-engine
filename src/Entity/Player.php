<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlayerRepository")
 */
class Player
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $points;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Game", inversedBy="players")
     */
    private $game;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GamePlayer", mappedBy="player")
     */
    private $gamePlayers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Question", mappedBy="correctPlayer")
     */
    private $correctQuestions;

    public function __construct()
    {
        $this->questionPlayers = new ArrayCollection();
        $this->gamePlayers = new ArrayCollection();
        $this->correctQuestions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $points): self
    {
        $this->points = $points;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return Collection|GamePlayer[]
     */
    public function getGamePlayers(): Collection
    {
        return $this->gamePlayers;
    }

    public function addGamePlayer(GamePlayer $gamePlayer): self
    {
        if (!$this->gamePlayers->contains($gamePlayer)) {
            $this->gamePlayers[] = $gamePlayer;
            $gamePlayer->setPlayer($this);
        }

        return $this;
    }

    public function removeGamePlayer(GamePlayer $gamePlayer): self
    {
        if ($this->gamePlayers->contains($gamePlayer)) {
            $this->gamePlayers->removeElement($gamePlayer);
            // set the owning side to null (unless already changed)
            if ($gamePlayer->getPlayer() === $this) {
                $gamePlayer->setPlayer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getCorrectQuestions(): Collection
    {
        return $this->correctQuestions;
    }

    public function addCorrectQuestion(Question $correctQuestion): self
    {
        if (!$this->correctQuestions->contains($correctQuestion)) {
            $this->correctQuestions[] = $correctQuestion;
            $correctQuestion->setCorrectPlayer($this);
        }

        return $this;
    }

    public function removeCorrectQuestion(Question $correctQuestion): self
    {
        if ($this->correctQuestions->contains($correctQuestion)) {
            $this->correctQuestions->removeElement($correctQuestion);
            // set the owning side to null (unless already changed)
            if ($correctQuestion->getCorrectPlayer() === $this) {
                $correctQuestion->setCorrectPlayer(null);
            }
        }

        return $this;
    }
}
