<?php

namespace App\Enums\Freepik\Image\FluxDev;

use App\Traits\BaseEnumTrait;

enum ColorEffectEnum: string
{
    use BaseEnumTrait;

    case SOFTHUE = 'softhue';
    case BW = 'b&w';
    case GOLDGLOW = 'goldglow';
    case VIBRANT = 'vibrant';
    case COLDNEON = 'coldneon';
}
