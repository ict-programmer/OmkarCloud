<?php

namespace App\Enums\Freepik\Image\ClassicFast;

use App\Traits\BaseEnumTrait;

enum StylingEffectLightningEnum: string
{
    use BaseEnumTrait;

    case STUDIO = 'studio';
    case WARM = 'warm';
    case CINEMATIC = 'cinematic';
    case VOLUMETRIC = 'volumetric';
    case GOLDEN_HOUR = 'golden-hour';
    case LONG_EXPOSURE = 'long-exposure';
    case COLD = 'cold';
    case IRIDESCENT = 'iridescent';
    case DRAMATIC = 'dramatic';
    case HARDLIGHT = 'hardlight';
    case REDSCALE = 'redscale';
    case INDOOR_LIGHT = 'indoor-light';
}
