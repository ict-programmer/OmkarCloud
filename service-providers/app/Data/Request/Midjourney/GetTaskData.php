<?php

namespace App\Data\Request\Midjourney;

use Spatie\LaravelData\Data;

class GetTaskData extends Data
{
    public function __construct(
        public string $task_id,
    ) {}
} 