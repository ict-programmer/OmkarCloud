<?php

namespace App\Enums\Freepik;

use App\Traits\BaseEnumTrait;

enum PeriodEnum: string
{
    use BaseEnumTrait;

    case LAST_MONTH = 'last-month';
    case LAST_QUARTER = 'last-quarter';
    case LAST_SEMESTER = 'last-semester';
    case LAST_YEAR = 'last-year';
}
