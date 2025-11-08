<?php

namespace App\Data\Request\Canva;

use Spatie\LaravelData\Data;

class GetFolderItemsData extends Data
{
  public function __construct(
    public string $folder_id,
    public ?string $continuation,
    public ?array $item_types,
    public ?string $sort_by
  ) {}
}
