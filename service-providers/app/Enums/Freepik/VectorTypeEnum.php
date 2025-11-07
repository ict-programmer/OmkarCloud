<?php

namespace App\Enums\Freepik;

use App\Traits\BaseEnumTrait;

enum VectorTypeEnum: string
{
    use BaseEnumTrait;

    case JPG = 'jpg';
    case AI = 'ai';
    case EPS = 'eps';
    case SVG = 'svg';
}
