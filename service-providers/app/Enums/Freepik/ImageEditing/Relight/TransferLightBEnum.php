<?php

namespace App\Enums\Freepik\ImageEditing\Relight;

use App\Traits\BaseEnumTrait;

enum TransferLightBEnum: string
{
    use BaseEnumTrait;

    case AUTOMATIC = 'automatic';
    case COMPOSITION = 'composition';
    case STRAIGHT = 'straight';
    case SMOOTH_IN = 'smooth_in';
    case SMOOTH_OUT = 'smooth_out';
    case SMOOTH_BOTH = 'smooth_both';
    case REVERSE_BOTH = 'reverse_both';
    case SOFT_IN = 'soft_in';
    case SOFT_OUT = 'soft_out';
    case SOFT_MID = 'soft_mid';
    case STRONG_MID = 'strong_mid';
    case STYLE_SHIFT = 'style_shift';
    case STRONG_SHIFT = 'strong_shift';
}
