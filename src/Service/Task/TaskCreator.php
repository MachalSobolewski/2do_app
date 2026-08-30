<?php

namespace App\Service\Task;

use App\Entity\BoardColumn;
use App\Entity\Task;
use App\Repository\BoardColumnRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class TaskCreator
{
    public function __construct(
        private EntityManagerInterface $em,
        private BoardColumnRepository $columnRepository,
    ) {
    }

    public function create(int $columnId, FormInterface $form): Task
    {
        /** @var BoardColumn $column */
        $column = $this->columnRepository->find($columnId);

        if (!$column) {
            throw new NotFoundHttpException('Column not found');
        }

        $task = $this->makeTask($form, $column);

        $this->em->persist($task);
        $this->em->flush();

        return $task;
    }

    public function makeTask(FormInterface $form, BoardColumn $column): Task
    {
        $task = new Task();

        return $task
            ->setBoardColumn($column)
            ->setName($form->get('name')->getData())
            ->setDescription($form->get('description')->getData());
    }
}
