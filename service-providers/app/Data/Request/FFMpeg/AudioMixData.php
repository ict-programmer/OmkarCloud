<?php

namespace App\Data\Request\FFMpeg;

use Spatie\LaravelData\Data;

class AudioMixData extends Data
{
    public function __construct(
        public array $audio_tracks
    ) {}
}
