<?php

namespace App\Data\Request\OmkarCloud;

use Spatie\LaravelData\Data;

class BusinessSearchData extends Data
{
    public function __construct(
        public string $query,
        public ?array $filters = null,     // ['city' => 'Tokyo', 'rating' => 4.2] etc.
        public ?string $format = 'json',   // 'json'|'csv'|'excel'
    ) {}
}
