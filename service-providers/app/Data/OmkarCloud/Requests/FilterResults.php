<?php

namespace App\Data\OmkarCloud\Requests;

readonly class FilterResults implements ArrayableRequest
{
    public function __construct(
        public ?string $taskId = null,
        public ?array $filters = null, // e.g. ['city'=>'Paris','rating'=>4]
        public ?string $format = 'json'
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'task_id' => $this->taskId,
            'filters' => $this->filters,
            'format'  => $this->format,
        ], fn($v) => $v !== null);
    }
}
