<?php

namespace App\Data\GettyImages;

use Spatie\LaravelData\Data;

class GenerateBackgroundsData extends Data
{
  public function __construct(
    public ?string $reference_file_registration_id,
    public ?string $prompt,
    public ?int $product_id,
    public ?string $project_code,
    public ?string $notes,
    public ?float $left_percentage,
    public ?float $right_percentage,
    public ?float $top_percentage,
    public ?float $bottom_percentage,
  ) {}
}
