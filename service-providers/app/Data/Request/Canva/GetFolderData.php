<?php

namespace App\Data\Request\Canva;

use Spatie\LaravelData\Data;

class GetFolderData extends Data
{
  public function __construct(
    public string $folder_id,
    public string $endpoint_interface
  ) {}
}
