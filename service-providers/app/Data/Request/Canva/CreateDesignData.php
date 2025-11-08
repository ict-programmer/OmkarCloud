<?php

namespace App\Data\Request\Canva;

use Spatie\LaravelData\Data;

class CreateDesignData extends Data
{
  public function __construct(
    public array $design_type,
    public string $asset_id,
    public string $title,
    public ?string $endpoint_interface = "generate"
  ) {}
}
