<?php

namespace App\Data\Request\Envato;

use Spatie\LaravelData\Data;

class DownloadPurchasedItemData extends Data
{
    public function __construct(
        public string $item_id,
    ) {}
} 