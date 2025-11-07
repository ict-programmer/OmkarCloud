<?php

namespace App\Data\GettyImages;

use Spatie\LaravelData\Data;

class InfluenceCompositionByImageData extends Data
{
  public function __construct(
    public ?string $reference_asset_id,
        public ?ReferenceGenerationData $reference_generation,
        public ?string $reference_file_registration_id,
        public ?string $prompt,
        public ?int $influence_level,
        public ?string $media_type,
        public ?string $mood,
        public ?int $seed,
        public ?int $product_id,
        public ?string $project_code,
        public ?string $notes,
  ) {}
}
