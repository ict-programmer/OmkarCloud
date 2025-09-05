<?php

namespace App\Enums\Freepik\ImageEditing\Relight;

use App\Traits\BaseEnumTrait;

enum TransferLightAEnum: string
{
    use BaseEnumTrait;

    case AUTOMATIC = 'automatic';
    case LOW = 'low';
    case MEDIUM = 'medium';
    case NORMAL = 'normal';
    case HIGH = 'high';
    case HIGH_ON_FACES = 'high_on_faces';
}
