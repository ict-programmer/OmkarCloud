<?php

namespace App\Enums\Freepik;

use App\Traits\BaseEnumTrait;

enum VectorStyleEnum: string
{
    use BaseEnumTrait;

    case WATERCOLOR = 'watercolor';
    case FLAT = 'flat';
    case CARTOON = 'cartoon';
    case GEOMETRIC = 'geometric';
    case GRADIENT = 'gradient';
    case ISOMETRIC = 'isometric';
    case THREE_D = '3d';
    case HAND_DRAWN = 'hand-drawn';
}
