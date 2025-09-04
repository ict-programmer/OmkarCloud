<?php

namespace App\Data\OmkarCloud\Requests;

readonly class TaskId implements ArrayableRequest
{
    public function __construct(
        public string $taskId,
        public ?string $format = 'json'
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'task_id' => $this->taskId,
            'format'  => $this->format,
        ], fn($v) => $v !== null);
    }
}
