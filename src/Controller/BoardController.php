<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Collection;

final class BoardController extends AbstractController
{
    #[Route('/board', name: 'app_board')]
    public function index(): Response
    {
        return $this->render('board/index.html.twig', [
            'boards' => [],
        ]);
    }
}
