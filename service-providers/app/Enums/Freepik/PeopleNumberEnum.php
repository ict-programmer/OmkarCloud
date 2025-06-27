<?php

namespace App\Enums\Freepik;

use App\Traits\BaseEnumTrait;

enum PeopleNumberEnum: string
{
    use BaseEnumTrait;

    case ONE = '1';
    case TWO = '2';
    case THREE = '3';
    case MORE_THAN_THREE = 'more_than_three';
}
