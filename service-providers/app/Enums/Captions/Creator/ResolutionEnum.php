<?php

namespace App\Enums\Captions\Creator;

use App\Traits\BaseEnumTrait;

enum ResolutionEnum: string
{
    use BaseEnumTrait;

    case FHD = 'fhd';
    case FOUR_K = '4k';
}
