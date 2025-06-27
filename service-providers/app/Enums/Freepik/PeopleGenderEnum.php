<?php

namespace App\Enums\Freepik;

use App\Traits\BaseEnumTrait;

enum PeopleGenderEnum: string
{
    use BaseEnumTrait;

    case MALE = 'male';
    case FEMALE = 'female';
}
