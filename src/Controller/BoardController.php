<?php

namespace App\Controller;

use App\Entity\Board;
use App\Entity\BoardColumn;
use App\Entity\Task;
use App\Form\BoardColumnType;
use App\Form\BoardType;
use App\Form\TaskType;
use App\Service\Board\BoardCreator;
use App\Service\Board\BoardGetter;
use App\Service\ModelRemover;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/board')]
final class BoardController extends AbstractController
{
    #[Route(
        path: '/',
        name: 'board_index',
        methods: ['GET']
    )]
    public function index(BoardGetter $boardGetter): Response
    {
        $form = $this->createForm(BoardType::class, new Board(), [
            'action' => $this->generateUrl('board_new'),
            'method' => 'POST',
        ]);

        $boards = $boardGetter->getList();

        return $this->render('board/index.html.twig', [
            'boards' => $boards,
            'form' => $form->createView(),
        ]);
    }

    #[Route(
        path: '/new',
        name: 'board_new',
        methods: ['POST']
    )]
    public function new(
        Request $request,
        BoardCreator $boardCreator,
    ): Response {
        $form = $this->createForm(BoardType::class, new Board());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $boardCreator->create($form);

            $this->addFlash('success', 'Nowa tablica została utworzona');

            return $this->redirectToRoute('board_index');
        }

        $this->addFlash('error', 'Nie udało się utworzyć tablicy. Sprawdź wprowadzone dane.');

        return $this->redirectToRoute('board_index');
    }

    #[Route(
        path: '/{id}',
        name: 'board_show',
        methods: ['GET']
    )]
    public function show(int $id, BoardGetter $boardGetter): Response
    {
        $board = $boardGetter->show($id);

        $columnForm = $this->createForm(BoardColumnType::class, new BoardColumn(), [
            'action' => $this->generateUrl('board_column_new', ['boardId' => $board->getId()]),
            'method' => 'POST',
        ]);

        $taskForm = $this->createForm(TaskType::class, new Task());

        return $this->render('board/show.html.twig', [
            'board' => $board,
            'columnForm' => $columnForm->createView(),
            'taskForm' => $taskForm->createView(),
        ]);
    }

    #[Route(
        path: '/{id}/delete',
        name: 'board_delete',
        methods: ['POST']
    )]
    public function delete(int $id, ModelRemover $remover): Response
    {
        $remover->delete($id, Board::class);

        return $this->redirectToRoute('board_index');
    }
}
