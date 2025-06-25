<?php

namespace App\Enums\Freepik\Image\ClassicFast;

use App\Traits\BaseEnumTrait;

enum StylingEffectFramingEnum: string
{
    use BaseEnumTrait;

    case PORTRAIT = 'portrait';
    case MACRO = 'macro';
    case PANORAMIC = 'panoramic';
    case AERIAL_VIEW = 'aerial-view';
    case CLOSE_UP = 'close-up';
    case CINEMATIC = 'cinematic';
    case HIGH_ANGLE = 'high-angle';
    case LOW_ANGLE = 'low-angle';
    case SYMMETRY = 'symmetry';
    case FISH_EYE = 'fish-eye';
    case FIRST_PERSON = 'first-person';
}
