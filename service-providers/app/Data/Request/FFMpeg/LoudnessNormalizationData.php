<?php

namespace App\Data\Request\FFMpeg;

use Spatie\LaravelData\Data;

class LoudnessNormalizationData extends Data
{
    public function __construct(
        public string $file_link,
        public float $target_lufs = -23.0,
        public float $lra = 7.0,
        public float $tp = -2.0
    ) {}
}
