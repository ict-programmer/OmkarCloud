<?php

namespace App\Data\Request\FFMpeg;

use Spatie\LaravelData\Data;

class AudioResampleData extends Data
{
    public function __construct(
        public string $input,
        public int $sample_rate,
        public int $channels
    ) {}
}
