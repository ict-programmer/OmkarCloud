<?php

namespace App\Enums\Freepik\Image\Mystic;

use App\Traits\BaseEnumTrait;

enum EngineEnum: string
{
    use BaseEnumTrait;

    case AUTOMATIC = 'automatic';
    case ILLUSIO = 'magnific_illusio';
    case SHARPY = 'magnific_sharpy';
    case SPARKLE = 'magnific_sparkle';
}
