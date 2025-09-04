<?php

namespace App\Data\OmkarCloud\Requests;

readonly class ManageTasks implements ArrayableRequest
{
    /** 
     * $action: 'start'|'abort'|'delete'
     * For 'start', you may embed a SearchQuery or SearchLinks payload in $payload.
     */
    public function __construct(
        public string $action,
        public ?string $taskId = null,
        public ?ArrayableRequest $payload = null
    ) {}

    public function toArray(): array
    {
        $arr = array_filter([
            'action'  => $this->action,
            'task_id' => $this->taskId,
        ], fn($v) => $v !== null);

        if ($this->payload) {
            $arr += $this->payload->toArray();
        }

        return $arr;
    }
}
