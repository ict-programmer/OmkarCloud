<?php

namespace App\Data\Request\ChatGPT;

use Spatie\LaravelData\Data;

class ImageGenerationData extends Data
{
    public function __construct(
        public string $model,
        public string $prompt,
        public int $n,
        public string $size,
    ) {}
}
