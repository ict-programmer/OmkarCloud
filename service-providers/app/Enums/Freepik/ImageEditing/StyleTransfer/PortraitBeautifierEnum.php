<?php

namespace App\Enums\Freepik\ImageEditing\StyleTransfer;

use App\Traits\BaseEnumTrait;

enum PortraitBeautifierEnum: string
{
    use BaseEnumTrait;

    case BEAUTIFY_FACE = 'beautify_face';
    case BEAUTIFY_FACE_MAX = 'beautify_face_max';
}
