<?php

namespace App\Data\Request\Asset;

use Spatie\LaravelData\Data;

class CreateAssetsData extends Data
{
    public function __construct(
        public string $name,
        public int $status,
    ) {}
}