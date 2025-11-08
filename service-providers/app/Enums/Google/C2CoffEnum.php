<?php

namespace App\Enums\Google;

use App\Traits\BaseEnumTrait;

enum C2CoffEnum: string
{
    use BaseEnumTrait;

    case ENABLED = '0';  // Simplified/Traditional Chinese Search enabled (default)
    case DISABLED = '1'; // Disabled
}
