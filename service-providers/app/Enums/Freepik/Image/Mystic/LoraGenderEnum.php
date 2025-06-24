<?php

namespace App\Enums\Freepik\Image\Mystic;

use App\Traits\BaseEnumTrait;

enum LoraGenderEnum: string
{
    use BaseEnumTrait;

    case MALE = 'male';
    case FEMALE = 'female';
    case NEUTRAL = 'neutral';
    case CUSTOM = 'custom';
}
