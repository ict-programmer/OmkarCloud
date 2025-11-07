<?php

namespace App\Enums\Freepik\Image\ClassicFast;

use App\Traits\BaseEnumTrait;

enum ImageSizeEnum: string
{
    use BaseEnumTrait;

    case SQUARE_1_1 = 'square_1_1';
    case CLASSIC_4_3 = 'classic_4_3';
    case TRADITIONAL_3_4 = 'traditional_3_4';
    case WIDESCREEN_16_9 = 'widescreen_16_9';
    case SOCIAL_STORY_9_16 = 'social_story_9_16';
    case SMARTPHONE_HORIZONTAL_20_9 = 'smartphone_horizontal_20_9';
    case SMARTPHONE_VERTICAL_9_20 = 'smartphone_vertical_9_20';
    case STANDARD_3_2 = 'standard_3_2';
    case PORTRAIT_2_3 = 'portrait_2_3';
    case HORIZONTAL_2_1 = 'horizontal_2_1';
    case VERTICAL_1_2 = 'vertical_1_2';
    case SOCIAL_5_4 = 'social_5_4';
    case SOCIAL_POST_4_5 = 'social_post_4_5';
}
