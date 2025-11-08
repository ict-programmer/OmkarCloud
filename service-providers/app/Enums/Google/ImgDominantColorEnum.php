<?php

namespace App\Enums\Google;

use App\Traits\BaseEnumTrait;

enum ImgDominantColorEnum: string
{
    use BaseEnumTrait;

    case BLACK = 'black';
    case BLUE = 'blue';
    case BROWN = 'brown';
    case GRAY = 'gray';
    case GREEN = 'green';
    case ORANGE = 'orange';
    case PINK = 'pink';
    case PURPLE = 'purple';
    case RED = 'red';
    case TEAL = 'teal';
    case WHITE = 'white';
    case YELLOW = 'yellow';
}
