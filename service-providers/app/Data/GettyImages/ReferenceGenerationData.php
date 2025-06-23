<?php

namespace App\Data\GettyImages;

use Spatie\LaravelData\Data;

class ReferenceGenerationData extends Data
{
    public function __construct(
        public string $generation_request_id,
        public int $index,
    ) {}
}