<?php

namespace App\Enums;

use App\Traits\BaseEnumTrait;

enum SortOrderEnum: string
{
    use BaseEnumTrait;

    case asc = 'asc';
    case desc = 'desc';
}
