<?php

namespace App\Data\Request\FFMpeg;

use Spatie\LaravelData\Data;

class AudioEncodeData extends Data
{
    public function __construct(
        public string $input,
        public string $codec,
        public string $bitrate
    ) {}
}
