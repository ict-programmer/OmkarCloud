<?php

namespace App\Enums\Google;

use App\Traits\BaseEnumTrait;

enum ImgColorTypeEnum: string
{
    use BaseEnumTrait;

    case COLOR = 'color';
    case GRAY = 'gray';
    case MONO = 'mono';
    case TRANS = 'trans';
}
