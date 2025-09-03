<?php

namespace App\Data\Request\FFMpeg;

use Spatie\LaravelData\Data;

class BitrateControlData extends Data
{
    public function __construct(
        public string $input,
        public int $crf,
        public string $preset,
        public string $cbr
    ) {}
}
