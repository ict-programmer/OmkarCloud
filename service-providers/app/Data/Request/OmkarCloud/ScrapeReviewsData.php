<?php

namespace App\Data\Request\OmkarCloud;

use Spatie\LaravelData\Data;

class ScrapeReviewsData extends Data
{
    public function __construct(
        public string $business_id,
        public ?int $max_results = null,
        public ?string $language = 'en',
        public ?string $format = 'json',
    ) {}
}
