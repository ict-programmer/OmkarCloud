<?php

namespace App\Data\Request\Perplexity;

use Spatie\LaravelData\Data;

class AiSearchData extends Data
{
    public function __construct(
        public string $query,
        public string $search_type,
        public ?string $model = 'sonar',
        public ?int $max_results = 0,
        public ?float $temperature = 0.2,
    ) {}
}
