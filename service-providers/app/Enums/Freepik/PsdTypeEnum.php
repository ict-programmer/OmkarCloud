<?php

namespace App\Enums\Freepik;

use App\Traits\BaseEnumTrait;

enum PsdTypeEnum: string
{
    use BaseEnumTrait;

    case JPG = 'jpg';
    case PSD = 'psd';
}
