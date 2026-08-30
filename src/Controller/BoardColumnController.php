<?php

namespace App\Controller;

use App\Entity\BoardColumn;
use App\Form\BoardColumnType;
use App\Service\BoardColumn\BoardColumnCreator;
use App\Service\BoardColumn\BoardColumnMover;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BoardColumnController extends AbstractController
{
    #[Route(
        path: '/board/{boardId}/column/new',
        name: 'board_column_new',
        methods: ['POST']
    )]
    public function new(
        int $boardId,
        Request $request,
        BoardColumnCreator $boardColumnCreator,
    ): Response {
        $form = $this->createForm(BoardColumnType::class, new BoardColumn());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $boardColumn = $boardColumnCreator->create($boardId, $form);

            return $this->render('board_column/single_column.html.twig', [
                'column' => $boardColumn,
            ]);
        }

        return new Response($form->getErrors(), 400);
    }

    #[Route('/column/{id}/move/{direction}', name: 'board_column_move', methods: ['POST'])]
    public function move(int $id, string $direction, BoardColumnMover $boardColumnMover): Response
    {
        try {
            $boardColumnMover->move($id, $direction);
        } catch (BadRequestException $badRequestException) {
            return new Response($badRequestException->getMessage(), 400);
        }

        return $this->json(['success' => true]);
    }
}
