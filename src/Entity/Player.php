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
     * @ORM\OneToMany(targetEntity="App\Entity\QuestionPlayer", mappedBy="Player", orphanRemoval=true)
     */
    private $questionPlayers;

    public function __construct()
    {
        $this->questionPlayers = new ArrayCollection();
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
     * @return Collection|QuestionPlayer[]
     */
    public function getQuestionPlayers(): Collection
    {
        return $this->questionPlayers;
    }

    public function addQuestionPlayer(QuestionPlayer $questionPlayer): self
    {
        if (!$this->questionPlayers->contains($questionPlayer)) {
            $this->questionPlayers[] = $questionPlayer;
            $questionPlayer->setPlayer($this);
        }

        return $this;
    }

    public function removeQuestionPlayer(QuestionPlayer $questionPlayer): self
    {
        if ($this->questionPlayers->contains($questionPlayer)) {
            $this->questionPlayers->removeElement($questionPlayer);
            // set the owning side to null (unless already changed)
            if ($questionPlayer->getPlayer() === $this) {
                $questionPlayer->setPlayer(null);
            }
        }

        return $this;
    }
}
