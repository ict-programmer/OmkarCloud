<?php

namespace App\Data\GettyImages;

use Spatie\LaravelData\Data;

class RemoveBackgroundData extends Data
{
  public function __construct(
    public string $reference_asset_id,
    public ?ReferenceGenerationData $reference_generation = null,
    public ?int $product_id = null,
    public ?string $project_code = null,
    public ?string $notes = null,
  ) {}
}
