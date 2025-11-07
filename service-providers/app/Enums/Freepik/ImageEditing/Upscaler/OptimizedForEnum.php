<?php

namespace App\Enums\Freepik\ImageEditing\Upscaler;

use App\Traits\BaseEnumTrait;

enum OptimizedForEnum: string
{
    use BaseEnumTrait;

    case STANDARD = 'standard';
    case SOFT_PORTRAITS = 'soft_portraits';
    case HARD_PORTRAITS = 'hard_portraits';
    case ART_N_ILLUSTRATION = 'art_n_illustration';
    case VIDEOGAME_ASSETS = 'videogame_assets';
    case NATURE_N_LANDSCAPES = 'nature_n_landscapes';
    case FILMS_N_PHOTOGRAPHY = 'films_n_photography';
    case RENDERS = '3d_renders';
    case SCI_FI_N_HORROR = 'science_fiction_n_horror';
}
