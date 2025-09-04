<?php

namespace App\Data\Request\OmkarCloud;

use Spatie\LaravelData\Data;

class FetchReviewsData extends Data
{
    public function __construct(
        public string $identifier,         // place_id or maps URL
        public ?int $limit = null,
        public ?string $format = 'json',
    ) {}
}
