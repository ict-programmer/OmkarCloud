<?php

namespace App\Data\Request\Canva;

use Spatie\LaravelData\Data;

class GetDesignData extends Data
{
  public function __construct(
    public string $design_id,
    public string $endpoint_interface
  ) {}
}
