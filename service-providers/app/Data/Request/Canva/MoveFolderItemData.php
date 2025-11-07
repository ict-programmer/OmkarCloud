<?php

namespace App\Data\Request\Canva;

use Spatie\LaravelData\Data;

class MoveFolderItemData extends Data
{
  public function __construct(
    public string $item_id,
    public string $to_folder_id,
  ) {}
}
