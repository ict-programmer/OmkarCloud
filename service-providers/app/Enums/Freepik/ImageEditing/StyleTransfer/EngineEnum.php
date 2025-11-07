<?php

namespace App\Enums\Freepik\ImageEditing\StyleTransfer;

use App\Traits\BaseEnumTrait;

enum EngineEnum: string
{
    use BaseEnumTrait;

    case BALANCED = 'balanced';
    case DEFINIO = 'definio';
    case ILLUSIO = 'illusio';
    case CARTOON_3D = '3d_cartoon';
    case COLORFUL_ANIME = 'colorful_anime';
    case CARICATURE = 'caricature';
    case REAL = 'real';
    case SUPER_REAL = 'super_real';
    case SOFTY = 'softy';
}
