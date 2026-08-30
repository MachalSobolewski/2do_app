<?php

namespace App\Twig\Components;

use App\Entity\Board;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Board')]
class BoardComponent
{
    public Board $board;
}
