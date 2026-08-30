<?php

namespace App\Repository;

use App\Entity\BoardColumn;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BoardColumnRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BoardColumn::class);
    }

    public function findByBoardIdAndPosition(int $boardId, int $position): ?BoardColumn
    {
        return $this->createQueryBuilder('boardColumn')
            ->join('boardColumn.board', 'board')
            ->where('board.id = :boardId')
            ->andWhere('boardColumn.position = :position')
            ->setParameter('boardId', $boardId)
            ->setParameter('position', $position)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
