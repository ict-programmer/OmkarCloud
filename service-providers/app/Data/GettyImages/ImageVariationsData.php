<?php

namespace App\Data\GettyImages;

use Spatie\LaravelData\Data;

class ImageVariationsData extends Data
{
    public function __construct(
        public ?int $product_id,
        public ?string $project_code,
        public ?string $notes,
    ) {}
}
