<?php

namespace App\Enums\Freepik;

use App\Traits\BaseEnumTrait;

enum PeopleEthnicityEnum: string
{
    use BaseEnumTrait;

    case SOUTH_ASIAN = 'south-asian';
    case MIDDLE_EASTERN = 'middle-eastern';
    case EAST_ASIAN = 'east-asian';
    case BLACK = 'black';
    case HISPANIC = 'hispanic';
    case INDIAN = 'indian';
    case WHITE = 'white';
    case MULTIRACIAL = 'multiracial';
    case SOUTHEAST_ASIAN = 'southeast-asian';
}
