<?php

namespace App\Data\Runwayml;

use Spatie\LaravelData\Data;

class VideoProcessingData extends Data
{
    public function __construct(
        public string $model,
        public string $prompt_image,
        public string $prompt_text,
        public int $seed,
        public int $duration,
        public int $width,
        public int $height
    ) {}
}
