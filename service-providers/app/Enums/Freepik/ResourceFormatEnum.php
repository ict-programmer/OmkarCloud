<?php

namespace App\Enums\Freepik;

use App\Traits\BaseEnumTrait;

enum ResourceFormatEnum: string
{
    use BaseEnumTrait;

    case PSD = 'psd';
    case AI = 'ai';
    case EPS = 'eps';
    case ATN = 'atn';
    case FONTS = 'fonts';
    case RESOURCES = 'resources';
    case PNG = 'png';
    case JPG = 'jpg';
    case RENDER_3D = '3d-render';
    case SVG = 'svg';
    case MOCKUP = 'mockup';
}
