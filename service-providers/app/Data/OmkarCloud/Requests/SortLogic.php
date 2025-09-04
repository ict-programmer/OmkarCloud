<?php

namespace App\Data\OmkarCloud\Requests;

readonly class SortLogic implements ArrayableRequest
{
    public function __construct(
        public ?string $taskId = null,
        public string $mode = 'best_customer', // future-proof
        public ?string $format = 'json'
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'task_id' => $this->taskId,
            'mode'    => $this->mode,
            'format'  => $this->format,
        ], fn($v) => $v !== null);
    }
}
