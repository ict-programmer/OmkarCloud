<?php

namespace App\Data\Request\Envato;

use Spatie\LaravelData\Data;

class ItemSearchData extends Data
{
    public function __construct(
        public string $site,
        public string $term,
    ) {}
} 