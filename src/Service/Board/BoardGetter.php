<?php

namespace App\Service\Board;

use App\Entity\Board;
use App\Repository\BoardRepository;

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

    public function show(int $id): ?Board
    {
        return $this->boardRepository->getOneById($id);
    }
}
