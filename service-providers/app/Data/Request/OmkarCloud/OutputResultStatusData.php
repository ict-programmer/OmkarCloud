<?php

namespace App\Data\Request\OmkarCloud;

use Spatie\LaravelData\Data;

class OutputResultStatusData extends Data
{
    public function __construct(
        public string $task_id,
    ) {}
}
