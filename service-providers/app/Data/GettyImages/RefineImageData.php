<?php

namespace App\Data\GettyImages;

use Spatie\LaravelData\Data;

class RefineImageData extends Data
{
  public function __construct(
    public ?string $reference_asset_id,
    public ?ReferenceGenerationData $reference_generation,
    public ?string $prompt,
    public ?int $product_id,
    public ?string $project_code,
    public ?string $notes,
    public ?string $mask_url,
  ) {}
}
