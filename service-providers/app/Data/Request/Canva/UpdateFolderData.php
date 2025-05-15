<?php

namespace App\Data\Request\Canva;

use Spatie\LaravelData\Data;

class UpdateFolderData extends Data
{
  public function __construct(
    public string $name,
    public string $folder_id,
    public string $endpoint_interface
  ) {}
}
