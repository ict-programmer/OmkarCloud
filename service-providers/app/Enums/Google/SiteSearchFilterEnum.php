<?php

namespace App\Enums\Google;

use App\Traits\BaseEnumTrait;

enum SiteSearchFilterEnum: string
{
    use BaseEnumTrait;

    case EXCLUDE = 'e';
    case INCLUDE = 'i';
}
