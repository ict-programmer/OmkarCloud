<?php

namespace App\Data\Request\FFMpeg;

use Spatie\LaravelData\Data;

class AudioProcessingData extends Data
{
    public function __construct(
        public string $file_link,
        public string $bitrate,
        public int $channels,
        public int $sample_rate
    ) {}
}
