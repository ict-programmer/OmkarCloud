<?php

namespace App\Enums\Freepik\ImageEditing\Upscaler;

use App\Traits\BaseEnumTrait;

enum ScaleFactorEnum: string
{
    use BaseEnumTrait;

    case X2 = '2x';
    case X4 = '4x';
    case X8 = '8x';
    case X16 = '16x';
}
