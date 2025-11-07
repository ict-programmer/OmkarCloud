<?php

namespace App\Data\Request\Envato;

use Spatie\LaravelData\Data;

class ItemDetailsData extends Data
{
    public function __construct(
        public string $item_id,
    ) {}
} 