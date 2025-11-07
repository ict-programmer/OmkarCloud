<?php

namespace App\Enums\Freepik\Image\Mystic;

use App\Traits\BaseEnumTrait;

enum ResolutionEnum: string
{
    use BaseEnumTrait;

    case K1 = '1k';
    case K2 = '2k';
    case K4 = '4k';
}
