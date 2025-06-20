<?php

namespace App\Enums\Freepik;

enum PeriodEnum: string
{
    case LAST_MONTH = 'last-month';
    case LAST_QUARTER = 'last-quarter';
    case LAST_SEMESTER = 'last-semester';
    case LAST_YEAR = 'last-year';
}
