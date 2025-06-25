<?php

namespace App\Enums\Freepik\Image\Imagen3;

use App\Traits\BaseEnumTrait;

enum PersonGenerationEnum: string
{
    use BaseEnumTrait;

    case DONT_ALLOW = 'dont_allow';
    case ALLOW_ADULT = 'allow_adult';
    case ALLOW_ALL = 'allow_all';
}
