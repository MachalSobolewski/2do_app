<?php

namespace App\Service\Task;

use App\Entity\BoardColumn;
use App\Entity\Task;
use App\Repository\BoardColumnRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class TaskMover
{
    public function __construct(
        private BoardColumnRepository $boardColumnRepository,
        private EntityManagerInterface $em,
        private TaskRepository $taskRepository,
    ) {
    }

    public function move(int $taskId, int $columnId): void
    {
        /** @var Task $task */
        $task = $this->taskRepository->find($taskId);

        if (!$task) {
            throw new NotFoundHttpException('Task not found');
        }
        /** @var BoardColumn $column */
        $column = $this->boardColumnRepository->find($columnId);

        if (!$column) {
            throw new NotFoundHttpException('Column not found');
        }

        $task->setBoardColumn($column);
        $this->em->flush();
    }
}
