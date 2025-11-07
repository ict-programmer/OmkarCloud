<?php

namespace App\Data\GettyImages;

use Spatie\LaravelData\Data;

class ImageGenerationData extends Data
{
    public function __construct(
        public ?string $prompt,
        public ?int $seed,
        public ?string $aspect_ratio,
        public ?string $media_type,
        public ?string $mood,
        public ?int $product_id,
        public ?string $project_code,
        public ?string $notes,
    ) {}
}
