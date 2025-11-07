<?php

namespace App\Enums\Freepik\Image\Mystic;

use App\Traits\BaseEnumTrait;

enum LoraCharacterQualityEnum: string
{
    use BaseEnumTrait;

    case HIGH = 'high';
    case ULTRA = 'ultra';
}
