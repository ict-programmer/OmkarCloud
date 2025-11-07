<?php

namespace App\Enums\Freepik\Image\ClassicFast;

use App\Traits\BaseEnumTrait;

enum StylingStyleEnum: string
{
    use BaseEnumTrait;

    case PHOTO = 'photo';
    case DIGITAL_ART = 'digital-art';
    case THREE_D = '3d';
    case PAINTING = 'painting';
    case LOW_POLY = 'low-poly';
    case PIXEL_ART = 'pixel-art';
    case ANIME = 'anime';
    case CYBERPUNK = 'cyberpunk';
    case COMIC = 'comic';
    case VINTAGE = 'vintage';
    case CARTOON = 'cartoon';
    case VECTOR = 'vector';
    case STUDIO_SHOT = 'studio-shot';
    case DARK = 'dark';
    case SKETCH = 'sketch';
    case MOCKUP = 'mockup';
    case TWO_THOUSAND_PONE = '2000s-pone';
    case SEVENTIES_VIBE = '70s-vibe';
    case WATERCOLOR = 'watercolor';
    case ART_NOUVEAU = 'art-nouveau';
    case ORIGAMI = 'origami';
    case SURREAL = 'surreal';
    case FANTASY = 'fantasy';
    case TRADITIONAL_JAPAN = 'traditional-japan';
}
