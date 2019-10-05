<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
class Question
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
    private $answer;

    /**
     * @ORM\Column(type="integer")
     */
    private $points;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $solution;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuestionPlayer", mappedBy="Question", orphanRemoval=true)
     */
    private $questionPlayers;

    public function __construct()
    {
        $this->player = new ArrayCollection();
        $this->questionPlayers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): self
    {
        $this->points = $points;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getSolution(): ?string
    {
        return $this->solution;
    }

    public function setSolution(string $solution): self
    {
        $this->solution = $solution;

        return $this;
    }

    public function __toString()
    {
        return $this->getAnswer() . ' - ' . $this->getSolution();
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
            $questionPlayer->setQuestion($this);
        }

        return $this;
    }

    public function removeQuestionPlayer(QuestionPlayer $questionPlayer): self
    {
        if ($this->questionPlayers->contains($questionPlayer)) {
            $this->questionPlayers->removeElement($questionPlayer);
            // set the owning side to null (unless already changed)
            if ($questionPlayer->getQuestion() === $this) {
                $questionPlayer->setQuestion(null);
            }
        }

        return $this;
    }
}
