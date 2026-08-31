<?php

namespace App\Tests\Service\Board;

use App\Entity\Board;
use App\Entity\BoardColumn;
use App\Service\Board\BoardCreator;
use App\Service\BoardColumn\BoardColumnCreator;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;

class BoardCreatorTest extends TestCase
{
    private EntityManagerInterface $em;
    private BoardColumnCreator $boardColumnCreator;
    private BoardCreator $boardCreator;

    protected function setUp(): void
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->boardColumnCreator = $this->createMock(BoardColumnCreator::class);

        $this->boardCreator = new BoardCreator($this->em, $this->boardColumnCreator);
    }

    public function testCreateSuccess(): void
    {
        $boardName = 'Moja Nowa Tablica';

        $nameFormMock = $this->createMock(FormInterface::class);
        $nameFormMock->expects($this->once())
            ->method('getData')
            ->willReturn($boardName);

        $formMock = $this->createMock(FormInterface::class);
        $formMock->expects($this->once())
            ->method('get')
            ->with('name')
            ->willReturn($nameFormMock);


        $this->boardColumnCreator->expects($this->exactly(3))
            ->method('makeColumn')
            ->willReturnOnConsecutiveCalls(
                new BoardColumn(),
                new BoardColumn(),
                new BoardColumn()
            );


        $this->em->expects($this->exactly(4))
            ->method('persist')
            ->with($this->logicalOr(
                $this->isInstanceOf(Board::class),
                $this->isInstanceOf(BoardColumn::class)
            ));

        $this->em->expects($this->once())
            ->method('flush');

        $this->boardCreator->create($formMock);
    }
}
