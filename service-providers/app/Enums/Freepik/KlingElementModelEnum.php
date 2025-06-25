<?php

namespace App\Enums\Freepik;

use App\Traits\BaseEnumTrait;

enum KlingElementModelEnum: string
{
    use BaseEnumTrait;

    case PRO = 'kling-elements-pro';
    case STD = 'kling-elements-std';
}
