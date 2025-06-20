<?php

namespace App\Enums\Freepik;

enum ResourceFormatEnum: string
{
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
