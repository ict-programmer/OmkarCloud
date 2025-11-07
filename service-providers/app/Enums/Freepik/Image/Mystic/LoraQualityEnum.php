<?php

namespace App\Enums\Freepik\Image\Mystic;

use App\Traits\BaseEnumTrait;

enum LoraQualityEnum: string
{
    use BaseEnumTrait;

    case MEDIUM = 'medium';
    case HIGH = 'high';
    case ULTRA = 'ultra';
}
