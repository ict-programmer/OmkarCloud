<?php

namespace App\Data\Request\FFMpeg;

use Spatie\LaravelData\Data;

class TranscodingData extends Data
{
    public function __construct(
        public string $file_link,
        public string $output_format = 'mp4'
    ) {}
}
