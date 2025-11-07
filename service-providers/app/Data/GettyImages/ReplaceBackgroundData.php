<?php

namespace App\Data\GettyImages;

use Spatie\LaravelData\Data;

class ReplaceBackgroundData extends Data
{
    public function __construct(
        public ?string $prompt,
        public ?string $reference_asset_id,
        public ?ReferenceGenerationData $reference_generation,
        public ?int $product_id,
        public ?string $media_type,
        public ?string $negative_prompt,
        public ?int $seed,
        public ?string $background_color,
        public ?string $project_code,
        public ?string $notes,
    ) {}
}
