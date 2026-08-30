<?php

namespace App\Service\BoardColumn;

use App\Entity\Board;
use App\Entity\BoardColumn;
use App\Enum\MoveDirection;
use App\Repository\BoardColumnRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

readonly class BoardColumnMover
{
    public function __construct(
        private EntityManagerInterface $em,
        private BoardColumnRepository $boardColumnRepository,
    ) {
    }

    public function move(int $columnId, string $direction): void
    {
        /** @var BoardColumn $column */
        $column = $this->boardColumnRepository->find($columnId);
        $moveDirection = MoveDirection::tryFrom($direction);

        $this->validate($column, $moveDirection);

        $board = $column->getBoard();

        $this->changePosition($column, $board, $moveDirection);
    }

    private function validate(?BoardColumn $column, ?MoveDirection $moveDirection): void
    {
        if (!$column) {
            throw new BadRequestException('Column not found');
        }

        if (!$moveDirection) {
            throw new BadRequestException('Wrong move direction');
        }
    }

    private function changePosition(BoardColumn $column, Board $board, MoveDirection $moveDirection): void
    {
        switch ($moveDirection) {
            case MoveDirection::Left:
                $position = $column->getPosition() - 1;
                break;
            case MoveDirection::Right:
                $position = $column->getPosition() + 1;
                break;
        }

        $nearest = $this->boardColumnRepository->findByBoardIdAndPosition($board->getId(), $position);

        if (!$nearest) {
            throw new BadRequestException('Wrong move direction');
        }

        $oldPosition = $column->getPosition();
        $column->setPosition($nearest->getPosition());
        $nearest->setPosition($oldPosition);
        $this->em->persist($column);
        $this->em->persist($nearest);
        $this->em->flush();
    }
}
