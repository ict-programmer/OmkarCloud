<?php

namespace App\Enums\Freepik\Image\Imagen3;

use App\Traits\BaseEnumTrait;

enum AspectRatioEnum: string
{
    use BaseEnumTrait;

    case SQUARE_1_1 = 'square_1_1';
    case SOCIAL_STORY_9_16 = 'social_story_9_16';
    case WIDESCREEN_16_9 = 'widescreen_16_9';
    case TRADITIONAL_3_4 = 'traditional_3_4';
    case CLASSIC_4_3 = 'classic_4_3';
}
