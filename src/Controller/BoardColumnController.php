<?php

namespace App\Controller;

use App\Entity\BoardColumn;
use App\Form\BoardColumnType;
use App\Service\BoardColumn\BoardColumnCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
