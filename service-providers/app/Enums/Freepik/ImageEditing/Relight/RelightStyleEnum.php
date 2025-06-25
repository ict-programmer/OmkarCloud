<?php

namespace App\Enums\Freepik\ImageEditing\Relight;

use App\Traits\BaseEnumTrait;

enum RelightStyleEnum: string
{
    use BaseEnumTrait;

    case STANDARD = 'standard';
    case DARKER_BUT_REALISTIC = 'darker_but_realistic';
    case CLEAN = 'clean';
    case SMOOTH = 'smooth';
    case BRIGHTER = 'brighter';
    case CONTRASTED_N_HDR = 'contrasted_n_hdr';
    case JUST_COMPOSITION = 'just_composition';
}
