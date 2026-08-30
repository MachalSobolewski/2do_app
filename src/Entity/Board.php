<?php

namespace App\Entity;

use App\Repository\BoardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BoardRepository::class)]
class Board
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: BoardColumn::class, mappedBy: 'board', cascade: ['persist', 'remove'])]
    private Collection $boardColumns;

    public function __construct()
    {
        $this->boardColumns = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBoardColumns(): Collection
    {
        return $this->boardColumns;
    }

    public function addBoardColumn(BoardColumn $boardColumn): self
    {
        $this->boardColumns->add($boardColumn);

        return $this;
    }

    public function countTask(): int
    {
        $total = 0;

        foreach ($this->getBoardColumns() as $column) {
            $total += $column->getTasks()->count();
        }

        return $total;
    }
}
