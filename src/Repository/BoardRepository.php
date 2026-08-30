<?php

namespace App\Repository;

use App\Entity\Board;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BoardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Board::class);
    }

    public function getAllOrderedByName(): array
    {
        return $this->createQueryBuilder('boards')
            ->select('boards', 'boardColumns')
            ->leftJoin('boards.boardColumns', 'boardColumns')
            ->orderBy('boards.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getOneById(int $id): ?Board
    {
        return $this->createQueryBuilder('board')
            ->select('board', 'boardColumn', 'task')
            ->leftJoin('board.boardColumns', 'boardColumn')
            ->leftJoin('boardColumn.tasks', 'task')
            ->where('board.id = :id')
            ->setParameter('id', $id)
            ->orderBy('boardColumn.position', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
