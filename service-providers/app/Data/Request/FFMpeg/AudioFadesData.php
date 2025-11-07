<?php

namespace App\Data\Request\FFMpeg;

use Spatie\LaravelData\Data;

class AudioFadesData extends Data
{
    public function __construct(
        public string $input,
        public ?float $fade_in_duration = null,
        public ?float $fade_out_duration = null
    ) {}
}
