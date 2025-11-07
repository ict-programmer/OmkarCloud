<?php

namespace App\Data\Request\OmkarCloud;

use Spatie\LaravelData\Data;

class FilteredSearchData extends Data
{
    public function __construct(
        public ?string $task_id = null,
        public string $mode = 'best_customer', // keep extensible
        public ?string $format = 'json',
    ) {}
}
