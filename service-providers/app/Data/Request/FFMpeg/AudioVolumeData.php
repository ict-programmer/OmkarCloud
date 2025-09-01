<?php

namespace App\Data\Request\FFMpeg;

use Spatie\LaravelData\Data;

class AudioVolumeData extends Data
{
    public function __construct(
        public string $input,
        public float $volume_factor
    ) {}
}
