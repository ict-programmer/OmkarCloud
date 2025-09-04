<?php

namespace App\Data\Request\OmkarCloud;

use Spatie\LaravelData\Data;

class ExportData extends Data
{
    public function __construct(
        public string $task_id,
        public string $format, // 'json'|'csv'|'excel'
    ) {}
}
