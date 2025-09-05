<?php

namespace App\Data\Request\OmkarCloud;

use Spatie\LaravelData\Data;

class SortByAdsReviewsWebsiteData extends Data
{
    public function __construct(
        public string $task_id,
        public ?string $mode = 'best_customer',
        public ?string $format = 'json',
    ) {}
}
