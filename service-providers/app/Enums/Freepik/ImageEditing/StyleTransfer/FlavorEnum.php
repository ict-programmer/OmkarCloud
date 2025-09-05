<?php

namespace App\Enums\Freepik\ImageEditing\StyleTransfer;

use App\Traits\BaseEnumTrait;

enum FlavorEnum: string
{
    use BaseEnumTrait;

    case FAITHFUL = 'faithful';
    case GEN_Z = 'gen_z';
    case PSYCHEDELIA = 'psychedelia';
    case DETAILY = 'detaily';
    case CLEAR = 'clear';
    case DONOTSTYLE = 'donotstyle';
    case DONOTSTYLE_SHARP = 'donotstyle_sharp';
}
