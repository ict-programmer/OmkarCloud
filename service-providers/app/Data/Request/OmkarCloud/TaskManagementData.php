<?php

namespace App\Data\Request\OmkarCloud;

use Spatie\LaravelData\Data;

class TaskManagementData extends Data
{
    public function __construct(
        public string $task_id,
        public array $filters,
        public ?string $format = 'json',
    ) {}
}
