<?php

namespace App\Enums\Freepik\Image\FluxDev;

use App\Traits\BaseEnumTrait;

enum FramingEffectEnum: string
{
    use BaseEnumTrait;

    case PORTRAIT = 'portrait';
    case LOWANGLE = 'lowangle';
    case MIDSHOT = 'midshot';
    case WIDESHOT = 'wideshot';
    case TILTSHOT = 'tiltshot';
    case AERIAL = 'aerial';
}
