<?php

namespace App\Enums\Freepik\Image\FluxDev;

use App\Traits\BaseEnumTrait;

enum LightningEffectEnum: string
{
    use BaseEnumTrait;

    case IRIDESCENT = 'iridescent';
    case DRAMATIC = 'dramatic';
    case GOLDENHOUR = 'goldenhour';
    case LONGEXPOSURE = 'longexposure';
    case INDORLIGHT = 'indorlight';
    case FLASH = 'flash';
    case NEON = 'neon';
}
