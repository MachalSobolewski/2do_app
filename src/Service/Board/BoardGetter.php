<?php

namespace App\Service\Board;

use App\Entity\Board;
use App\Repository\BoardRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class BoardGetter
{
    public function __construct(
        private BoardRepository $boardRepository,
    ) {
    }

    public function getList(): array
    {
        return $this->boardRepository->getAllOrderedByName();
    }

    public function show(int $id): Board
    {
        $board = $this->boardRepository->getOneById($id);

        if (!$board) {
            throw new NotFoundHttpException('Nie znaleziono tablicy');
        }

        return $board;
    }
}
