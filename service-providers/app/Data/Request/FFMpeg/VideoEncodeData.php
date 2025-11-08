<?php

namespace App\Data\Request\FFMpeg;

use Spatie\LaravelData\Data;

class VideoEncodeData extends Data
{
    public function __construct(
        public string $input,
        public string $codec,
        public array $params
    ) {}
}
