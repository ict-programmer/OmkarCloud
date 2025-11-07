<?php

namespace App\Data\Request\Canva;

use Spatie\LaravelData\Data;

class ListDesignsData extends Data
{
  public function __construct(
    public ?string $continuation,
    public ?string $endpoint_interface = "generate"
  ) {}
}
