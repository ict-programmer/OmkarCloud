<?php

namespace App\Data\Request\Claude;

use Spatie\LaravelData\Data;

class DataAnalysisInsightData extends Data
{
    public function __construct(
        public array $data,
        public string $task,
        public int $max_tokens,
        public ?string $model = null
    ) {}
}
