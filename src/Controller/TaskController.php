<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Service\ModelRemover;
use App\Service\Task\TaskCreator;
use App\Service\Task\TaskMover;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TaskController extends AbstractController
{
    #[Route(
        path: '/column/{columnId}/task/new',
        name: 'task_new',
        methods: ['POST']
    )]
    public function new(
        int $columnId,
        Request $request,
        TaskCreator $taskCreator,
    ): Response {
        $form = $this->createForm(TaskType::class, new Task());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $taskCreator->create($columnId, $form);

            return $this->render('task/single_task.html.twig', [
                'task' => $task,
            ]);
        }

        return new Response($form->getErrors(), 400);
    }

    #[Route('/task/{taskId}/move-to/{targetColumnId}', name: 'task_move', methods: ['POST'])]
    public function moveTask(int $taskId, int $targetColumnId, TaskMover $taskMover): Response
    {
        $taskMover->move($taskId, $targetColumnId);

        return $this->json(['success' => true]);
    }

    #[Route(
        path: 'task/{id}/delete',
        name: 'task_delete',
        methods: ['POST']
    )]
    public function delete(int $id, ModelRemover $remover): Response
    {
        $remover->delete($id, Task::class);

        return $this->json(['success' => true]);
    }
}
