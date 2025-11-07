<?php

namespace App\Enums\Freepik\Image\Imagen3;

use App\Traits\BaseEnumTrait;

enum SafetySettingsEnum: string
{
    use BaseEnumTrait;

    case BLOCK_LOW_AND_ABOVE = 'block_low_and_above';
    case BLOCK_MEDIUM_AND_ABOVE = 'block_medium_and_above';
    case BLOCK_ONLY_HIGH = 'block_only_high';
    case BLOCK_NONE = 'block_none';
}
