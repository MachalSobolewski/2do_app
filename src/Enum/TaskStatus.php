<?php

namespace App\Enum;

enum TaskStatus: string
{
    case Pending = 'pending';
    case Completed = 'completed';
}
