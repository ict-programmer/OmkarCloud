<?php

namespace App\Data\Request\Shutterstock;

use Spatie\LaravelData\Data;

class SearchImagesData extends Data
{
    public function __construct(
        public string $query,
        public string $orientation,
    ) {}
} 