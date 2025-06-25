<?php

namespace App\Enums\Freepik\ImageEditing\StyleTransfer;

use App\Traits\BaseEnumTrait;

enum PortraitStyleEnum: string
{
    use BaseEnumTrait;

    case STANDARD = 'standard';
    case POP = 'pop';
    case SUPER_POP = 'super_pop';
}
