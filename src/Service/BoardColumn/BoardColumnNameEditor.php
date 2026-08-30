<?php

namespace App\Service\BoardColumn;

use App\Entity\BoardColumn;
use App\Form\BoardColumnType;
use App\Repository\BoardColumnRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class BoardColumnNameEditor
{
    public function __construct(
        private BoardColumnRepository $boardColumnRepository,
        private FormFactoryInterface $formFactory,
        private EntityManagerInterface $em,
    ) {
    }

    public function edit(int $id, Request $request): BoardColumn
    {
        /** @var BoardColumn $column */
        $column = $this->boardColumnRepository->find($id);

        if (!$column) {
            throw new NotFoundHttpException('Column not found.');
        }

        $form = $this->formFactory->create(BoardColumnType::class, $column);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
        }

        return $column;
    }
}
