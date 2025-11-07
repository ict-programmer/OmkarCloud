<?php

namespace App\Enums\Freepik\Image\ReimagineFlux;

use App\Traits\BaseEnumTrait;

enum ImaginationTypeEnum: string
{
    use BaseEnumTrait;

    case WILD = 'wild';
    case SUBTLE = 'subtle';
    case VIVID = 'vivid';
}
