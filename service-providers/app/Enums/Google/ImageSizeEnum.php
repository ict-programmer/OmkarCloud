<?php

namespace App\Enums\Google;

use App\Traits\BaseEnumTrait;

enum ImageSizeEnum: string
{
    use BaseEnumTrait;

    case HUGE = 'huge';
    case ICON = 'icon';
    case LARGE = 'large';
    case MEDIUM = 'medium';
    case SMALL = 'small';
    case XLARGE = 'xlarge';
    case XXLARGE = 'xxlarge';
}
