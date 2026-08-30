<?php

namespace App\Service\Board;

use App\Entity\Board;
use App\Entity\BoardColumn;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

readonly class BoardCreator
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function create(FormInterface $form): void
    {
        $board = new Board();

        $board->setName($form->get('name')->getData());
        $this->em->persist($board);

        $defaultColumns = ['Do zrobienia', 'W trakcie', 'Zrobione'];

        foreach ($defaultColumns as $name) {
            $column = new BoardColumn();
            $column->setName($name);
            $column->setBoard($board);

            $this->em->persist($column);
        }

        $this->em->flush();
    }
}
