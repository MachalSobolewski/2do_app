<?php

namespace App\Service\Board;

use App\Entity\Board;
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
        $this->em->flush();
    }
}
