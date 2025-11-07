<?php

namespace App\Enums\Freepik;

use App\Traits\BaseEnumTrait;

enum PeopleAgeEnum: string
{
    use BaseEnumTrait;

    case INFANT = 'infant';
    case CHILD = 'child';
    case TEEN = 'teen';
    case YOUNG_ADULT = 'young-adult';
    case ADULT = 'adult';
    case SENIOR = 'senior';
    case ELDER = 'elder';
}
