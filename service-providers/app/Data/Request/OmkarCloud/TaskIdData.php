<?php

namespace App\Data\Request\OmkarCloud;

use Spatie\LaravelData\Data;

class TaskIdData extends Data
{
    public function __construct(
        public string $task_id,
        public ?string $format = 'json',
    ) {}
}
