<?php

namespace App\Data\Request\Midjourney;

use Spatie\LaravelData\Data;

class ImageGenerationData extends Data
{
    public function __construct(
        public string $prompt,
        public ?string $aspect_ratio = '1:1'
    ) {}
} 