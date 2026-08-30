<?php

namespace App\Service\Board;

use App\Repository\BoardRepository;
use Doctrine\Common\Collections\Collection;

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
}
