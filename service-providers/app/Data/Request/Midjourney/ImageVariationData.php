<?php

namespace App\Data\Request\Midjourney;

use Spatie\LaravelData\Data;

class ImageVariationData extends Data
{
    public function __construct(
        public string $origin_task_id,
        public string $index,
        public string $prompt,
    ) {}
} 