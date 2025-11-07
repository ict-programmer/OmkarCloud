<?php

namespace App\Data\Request\FFMpeg;

use Spatie\LaravelData\Data;

class FrameExtractionData extends Data
{
    public function __construct(
        public string $input_file,
        public float $frame_rate
    ) {}
}
