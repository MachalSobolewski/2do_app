<?php

namespace App\Entity;

use App\Repository\BoardColumnRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BoardColumnRepository::class)]
class BoardColumn
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: Board::class, inversedBy: 'boardColumns')]
    private Board $board;

    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'boardColumn', cascade: ['persist', 'remove'])]
    private Collection $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
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

    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        $this->tasks->add($task);

        return $this;
    }

    public function setBoard(Board $board): self
    {
        $this->board = $board;

        return $this;
    }

    public function getBoard(): Board
    {
        return $this->board;
    }
}
