<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HistoryRepository")
 */
class History
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $score;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user", inversedBy="histories")
     */
    private $relations;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="histories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $relation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScore(): ?string
    {
        return $this->score;
    }

    public function setScore(string $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getRelations(): ?user
    {
        return $this->relations;
    }

    public function setRelations(?user $relations): self
    {
        $this->relations = $relations;

        return $this;
    }

    public function getRelation(): ?Categorie
    {
        return $this->relation;
    }

    public function setRelation(?Categorie $relation): self
    {
        $this->relation = $relation;

        return $this;
    }
}
