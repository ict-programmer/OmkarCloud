<?php

namespace App\Enums\Freepik\Image\Mystic;

use App\Traits\BaseEnumTrait;

enum ModelEnum: string
{
    use BaseEnumTrait;

    case REALISM = 'realism';
    case FLUID = 'fluid';
    case ZEN = 'zen';
}
