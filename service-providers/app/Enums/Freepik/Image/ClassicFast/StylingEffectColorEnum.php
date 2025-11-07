<?php

namespace App\Enums\Freepik\Image\ClassicFast;

use App\Traits\BaseEnumTrait;

enum StylingEffectColorEnum: string
{
    use BaseEnumTrait;

    case BW = 'b&w';
    case PASTEL = 'pastel';
    case SEPIA = 'sepia';
    case DRAMATIC = 'dramatic';
    case VIBRANT = 'vibrant';
    case ORANGE_TEAL = 'orange&teal';
    case FILM_FILTER = 'film-filter';
    case SPLIT = 'split';
    case ELECTRIC = 'electric';
    case PASTEL_PINK = 'pastel-pink';
    case GOLD_GLOW = 'gold-glow';
    case AUTUMN = 'autumn';
    case MUTED_GREEN = 'muted-green';
    case DEEP_TEAL = 'deep-teal';
    case DUOTONE = 'duotone';
    case TERRACOTTA_TEAL = 'terracotta&teal';
    case RED_BLUE = 'red&blue';
    case COLD_NEON = 'cold-neon';
    case BURGUNDY_BLUE = 'burgundy&blue';
}
