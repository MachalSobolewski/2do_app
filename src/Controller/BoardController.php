<?php

namespace App\Controller;

use App\Entity\Board;
use App\Form\BoardType;
use App\Service\Board\BoardCreator;
use App\Service\Board\BoardGetter;
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
        $board = new Board();
        $form = $this->createForm(BoardType::class, $board, [
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
        methods: ['POST'])]
    public function new(
        Request $request,
        BoardCreator $boardCreator,
    ): Response {
        $post = new Board();
        $form = $this->createForm(BoardType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $boardCreator->create($form);

            $this->addFlash('success', 'Nowa tablica została utworzona');

            return $this->redirectToRoute('board_index');
        }

        $this->addFlash('error', 'Nie udało się utworzyć tablicy. Sprawdź wprowadzone dane.');

        return $this->redirectToRoute('app_board_index');
    }

    #[Route('/board/{id}', name: 'board_show', methods: ['GET'])]
    public function show(int $id, BoardGetter $boardGetter): Response
    {
        $board = $boardGetter->show($id);

        if (!$board) {
            throw $this->createNotFoundException('Nie znaleziono takiej tablicy.');
        }

        return $this->render('board/show.html.twig', [
            'board' => $board,
        ]);
    }
}
