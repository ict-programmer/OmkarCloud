<?php

namespace App\Data\OmkarCloud\Requests;

readonly class ExportData implements ArrayableRequest
{
    public function __construct(
        public string $taskId,
        public string $format // 'json'|'csv'|'excel'
    ) {}

    public function toArray(): array
    {
        return [
            'task_id' => $this->taskId,
            'format'  => $this->format,
        ];
    }
}
