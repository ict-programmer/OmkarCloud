<?php

namespace App\Data\Request\FFMpeg;

use Spatie\LaravelData\Data;

class ImageProcessingData extends Data
{
    public function __construct(
        public string $file_link,
        public int $width,
        public int $height
    ) {}
}
