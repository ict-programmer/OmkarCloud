<?php

namespace App\Data\Request\Envato;

use Spatie\LaravelData\Data;

class PopularItemsData extends Data
{
    public function __construct(
        public string $site,
    ) {}
} 