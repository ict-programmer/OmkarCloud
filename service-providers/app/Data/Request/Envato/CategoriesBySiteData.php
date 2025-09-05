<?php

namespace App\Data\Request\Envato;

use Spatie\LaravelData\Data;

class CategoriesBySiteData extends Data
{
    public function __construct(
        public string $site,
    ) {}
} 