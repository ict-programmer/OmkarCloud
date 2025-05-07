<?php

namespace App\Data\Request\Asset;

use Spatie\LaravelData\Data;

class DeleteAssetsData extends Data
{
    public function __construct(
        public string $id,
    ) {}
}