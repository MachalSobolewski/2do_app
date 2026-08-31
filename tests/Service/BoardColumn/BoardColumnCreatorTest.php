<?php

namespace App\Tests\Service\BoardColumn;

use App\Entity\Board;
use App\Entity\BoardColumn;
use App\Repository\BoardRepository;
use App\Service\BoardColumn\BoardColumnCreator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BoardColumnCreatorTest extends TestCase
{
    private EntityManagerInterface $em;
    private BoardRepository $boardRepository;
    private BoardColumnCreator $creator;

    protected function setUp(): void
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->boardRepository = $this->createMock(BoardRepository::class);

        $this->creator = new BoardColumnCreator($this->em, $this->boardRepository);
    }

    public function testCreateSuccess(): void
    {
        $boardId = 1;
        $columnName = 'Do zrobienia';
        $existingColumnsCount = 3;

        $board = $this->createMock(Board::class);
        $columnsCollection = $this->createMock(ArrayCollection::class);

        $columnsCollection->expects($this->once())
            ->method('count')
            ->willReturn($existingColumnsCount);

        $board->expects($this->once())
            ->method('getBoardColumns')
            ->willReturn($columnsCollection);

        $this->boardRepository->expects($this->once())
            ->method('find')
            ->with($boardId)
            ->willReturn($board);

        $subForm = $this->createMock(FormInterface::class);
        $subForm->expects($this->once())
            ->method('getData')
            ->willReturn($columnName);

        $form = $this->createMock(FormInterface::class);
        $form->expects($this->once())
            ->method('get')
            ->with('name')
            ->willReturn($subForm);

        $this->em->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(BoardColumn::class));

        $this->em->expects($this->once())
            ->method('flush');

        $result = $this->creator->create($boardId, $form);

        $this->assertInstanceOf(BoardColumn::class, $result);
        $this->assertEquals($columnName, $result->getName());
        $this->assertEquals($existingColumnsCount, $result->getPosition());
        $this->assertSame($board, $result->getBoard());
    }

    public function testCreateThrowsNotFoundExceptionWhenBoardDoesNotExist(): void
    {
        $boardId = 999;
        $form = $this->createMock(FormInterface::class);

        $this->boardRepository->expects($this->once())
            ->method('find')
            ->with($boardId)
            ->willReturn(null);

        $this->em->expects($this->never())->method('persist');
        $this->em->expects($this->never())->method('flush');

        $this->expectException(NotFoundHttpException::class);

        $this->creator->create($boardId, $form);
    }
}
