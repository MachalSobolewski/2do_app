<?php

namespace App\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ModelRemover
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function delete(int $id, string $className): void
    {
        $model = $this->em->getRepository($className)->find($id);

        if(!$model){
            throw new NotFoundHttpException('Model not found');
        }

        $this->em->remove($model);
        $this->em->flush();
    }
}
