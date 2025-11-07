<?php

namespace App\Enums\Google;

use App\Traits\BaseEnumTrait;

enum FilterEnum: string
{
    use BaseEnumTrait;

    case OFF = '0'; // Turns off duplicate content filter
    case ON = '1';  // Turns on duplicate content filter
}
