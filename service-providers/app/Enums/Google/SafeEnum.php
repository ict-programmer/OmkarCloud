<?php

namespace App\Enums\Google;

use App\Traits\BaseEnumTrait;

enum SafeEnum: string
{
    use BaseEnumTrait;

    case ACTIVE = 'active';
    case OFF = 'off';
}
