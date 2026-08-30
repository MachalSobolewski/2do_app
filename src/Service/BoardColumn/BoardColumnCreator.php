<?php

namespace App\Service\BoardColumn;

use App\Entity\Board;
use App\Entity\BoardColumn;
use App\Repository\BoardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class BoardColumnCreator
{
    public function __construct(
        private EntityManagerInterface $em,
        private BoardRepository $boardRepository,
    ) {
    }

    public function create(int $boardId, FormInterface $form): BoardColumn
    {
        /** @var Board $board */
        $board = $this->boardRepository->find($boardId);

        if (!$board) {
            throw new NotFoundHttpException('Board not found');
        }

        $boardColumn = $this->makeColumn($form->get('name')->getData(), $board->getBoardColumns()->count(), $board);

        $this->em->persist($boardColumn);

        $this->em->flush();

        return $boardColumn;
    }

    public function makeColumn(string $name, int $position, Board $board): BoardColumn
    {
        $boardColumn = new BoardColumn();

        return $boardColumn
            ->setName($name)
            ->setPosition($position)
            ->setBoard($board)
        ;
    }
}
