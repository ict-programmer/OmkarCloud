<?php

namespace App\Enums\Google;

use App\Traits\BaseEnumTrait;

enum ImgTypeEnum: string
{
    use BaseEnumTrait;

    case CLIPART = 'clipart';
    case FACE = 'face';
    case LINEART = 'lineart';
    case STOCK = 'stock';
    case PHOTO = 'photo';
    case ANIMATED = 'animated';
}
