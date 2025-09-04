<?php

namespace App\Data\Request\OmkarCloud;

use Spatie\LaravelData\Data;

class ManageTasksData extends Data
{
    /**
     * action: 'start'|'abort'|'delete'
     * For 'start', pass either (query, filters, format) OR (links, filters, format).
     */
    public function __construct(
        public string $action,
        public ?string $task_id = null,

        // optional start payload
        public ?string $query = null,
        public ?array $links = null,
        public ?array $filters = null,
        public ?string $format = 'json',
    ) {}
}
