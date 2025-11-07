<?php

namespace App\Enums\Freepik;

use App\Traits\BaseEnumTrait;

enum KlingVideoDurationEnum: int
{
    use BaseEnumTrait;

    case FIVE_MINUTES = 5;
    case TEN_MINUTES = 10;
}
