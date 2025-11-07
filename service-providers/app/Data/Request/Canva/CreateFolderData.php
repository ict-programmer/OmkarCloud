<?php

namespace App\Data\Request\Canva;

use Spatie\LaravelData\Data;

class CreateFolderData extends Data
{
  public function __construct(
    public string $name,
    public string $parent_folder_id,
    public ?string $endpoint_interface = "generate"
  ) {}
}
