<?php

namespace App\Data\Request\OmkarCloud;

use Spatie\LaravelData\Data;

class DetailedResultViewData extends Data
{
    public function __construct(
        public string $task_id,
        public ?bool $include_raw = false,
        public ?string $format = 'json',
    ) {}
}
