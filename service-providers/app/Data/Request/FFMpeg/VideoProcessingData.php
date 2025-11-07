<?php

namespace App\Data\Request\FFMpeg;

use Spatie\LaravelData\Data;

class VideoProcessingData extends Data
{
    public function __construct(
        public string $file_link,
        public string $resolution,
        public string $bitrate,
        public int $frame_rate
    ) {}
}
