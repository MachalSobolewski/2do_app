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
            throw new NotFoundHttpException('Nie znaleziono tablicy');
        }

        $boardColumn = new BoardColumn();

        $boardColumn
            ->setName($form->get('name')->getData())
            ->setBoard($board)
        ;

        $this->em->persist($boardColumn);

        $this->em->flush();

        return $boardColumn;
    }
}
