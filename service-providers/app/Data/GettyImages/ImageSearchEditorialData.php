<?php

namespace App\Data\GettyImages;

use Spatie\LaravelData\Data;

class ImageSearchEditorialData extends Data
{
    public function __construct(
        public string $phrase,
        public ?array $fields,
    ) {}
}
