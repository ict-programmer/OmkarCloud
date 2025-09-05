<?php

namespace App\Enums\Freepik;

use App\Traits\BaseEnumTrait;

enum ColorEnum: string
{
    use BaseEnumTrait;

    case BLACK = 'black';
    case BLUE = 'blue';
    case GRAY = 'gray';
    case GREEN = 'green';
    case ORANGE = 'orange';
    case RED = 'red';
    case WHITE = 'white';
    case YELLOW = 'yellow';
    case PURPLE = 'purple';
    case CYAN = 'cyan';
    case PINK = 'pink';
}
