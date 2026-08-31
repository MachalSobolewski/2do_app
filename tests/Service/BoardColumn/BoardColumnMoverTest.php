<?php

namespace App\Tests\Service\BoardColumn;

use App\Entity\Board;
use App\Entity\BoardColumn;
use App\Enum\MoveDirection;
use App\Repository\BoardColumnRepository;
use App\Service\BoardColumn\BoardColumnMover;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class BoardColumnMoverTest extends TestCase
{
    private EntityManagerInterface $em;
    private BoardColumnRepository $repository;
    private BoardColumnMover $mover;

    protected function setUp(): void
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->repository = $this->createMock(BoardColumnRepository::class);

        $this->mover = new BoardColumnMover($this->em, $this->repository);
    }

    public function testMoveSuccessToRight(): void
    {
        $columnId = 10;
        $boardId = 5;

        $board = $this->createMock(Board::class);
        $board->method('getId')->willReturn($boardId);

        $column = new BoardColumn();
        $column->setPosition(2);
        $column->setBoard($board);

        $nearestColumn = new BoardColumn();
        $nearestColumn->setPosition(3);
        $nearestColumn->setBoard($board);

        $this->repository->expects($this->once())
            ->method('find')
            ->with($columnId)
            ->willReturn($column);

        $this->repository->expects($this->once())
            ->method('findByBoardIdAndPosition')
            ->with($boardId, 3) // Pozycja 2 + 1 = 3
            ->willReturn($nearestColumn);

        $persistedObjects = [];
        $this->em->expects($this->exactly(2))
            ->method('persist')
            ->willReturnCallback(function (BoardColumn $entity) use (&$persistedObjects) {
                $persistedObjects[] = $entity;
            });

        $this->em->expects($this->once())->method('flush');

        $this->mover->move($columnId, 'right');

        $this->assertEquals(3, $column->getPosition());
        $this->assertEquals(2, $nearestColumn->getPosition());
        $this->assertCount(2, $persistedObjects);
        $this->assertSame($column, $persistedObjects[0]);
        $this->assertSame($nearestColumn, $persistedObjects[1]);
    }
}
