<?php

namespace App\Data\Request\FFMpeg;

use Spatie\LaravelData\Data;

class VideoTrimmingData extends Data
{
    public function __construct(
        public string $file_link,
        public string $start_time,
        public string $end_time
    ) {}
}
