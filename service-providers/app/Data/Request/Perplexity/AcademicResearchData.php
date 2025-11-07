<?php

namespace App\Data\Request\Perplexity;

use Spatie\LaravelData\Data;

class AcademicResearchData extends Data
{
    public function __construct(
        public string $query,
        public ?string $model = 'sonar-deep-research',
        public ?int $max_results = 0,
        public ?string $search_type = 'academic',
    ) {}
}
