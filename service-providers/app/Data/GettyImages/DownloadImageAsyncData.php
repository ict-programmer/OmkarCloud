<?php

namespace App\Data\GettyImages;

use Spatie\LaravelData\Data;

class DownloadImageAsyncData extends Data
{
    public function __construct(
        public ?string $notes,
        public ?string $project_code,
        public ?string $size_name,
        public ?int $product_id,
    ) {}
}
