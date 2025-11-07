<?php

namespace App\Enums\Freepik\ImageEditing\Relight;

use App\Traits\BaseEnumTrait;

enum RelightEngineEnum: string
{
    use BaseEnumTrait;

    case AUTOMATIC = 'automatic';
    case BALANCED = 'balanced';
    case COOL = 'cool';
    case REAL = 'real';
    case ILLUSIO = 'illusio';
    case FAIRY = 'fairy';
    case COLORFUL_ANIME = 'colorful_anime';
    case HARD_TRANSFORM = 'hard_transform';
    case SOFTY = 'softy';
}
