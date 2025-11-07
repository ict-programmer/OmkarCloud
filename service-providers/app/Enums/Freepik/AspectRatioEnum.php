<?php

namespace App\Enums\Freepik;

use App\Traits\BaseEnumTrait;

enum AspectRatioEnum: string
{
    use BaseEnumTrait;

    case WIDESCREEN_16_9 = 'widescreen_16_9';
    case SOCIAL_STORY_9_16 = 'social_story_9_16';
    case SQUARE_1_1 = 'square_1_1';
}
