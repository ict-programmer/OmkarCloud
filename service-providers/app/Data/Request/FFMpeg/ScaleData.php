<?php

namespace App\Data\Request\FFMpeg;

use Spatie\LaravelData\Data;

class ScaleData extends Data
{
    public function __construct(
        public string $input,
        public string $resolution_target
    ) {}
}
