<?php

namespace App\Data\Request\OmkarCloud;

use Spatie\LaravelData\Data;

class BusinessSearchData extends Data
{
    public function __construct(
        public string $query,
        public ?string $location = null,
        public ?int $radius_km = null,
        public ?int $max_results = null,
        public ?string $format = 'json',
        public ?string $language = 'en',
    ) {}
}
