<?php

namespace App\Enums\Freepik\ImageEditing\Upscaler;

use App\Traits\BaseEnumTrait;

enum UpscaleEngineEnum: string
{
    use BaseEnumTrait;

    case AUTOMATIC = 'automatic';
    case ILLUSIO = 'magnific_illusio';
    case SHARPY = 'magnific_sharpy';
    case SPARKLE = 'magnific_sparkle';
}
