<?php

namespace App\Data\Request\OmkarCloud;

use Spatie\LaravelData\Data;

class FilterResultsData extends Data
{
    public function __construct(
        public ?string $task_id = null,
        public ?array $filters = null,
        public ?string $format = 'json',
    ) {}
}
