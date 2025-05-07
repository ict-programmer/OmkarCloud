<?php

namespace App\Data\Request\DeepSeek;

use Spatie\LaravelData\Data;

class mathematicalReasoningData extends Data
{
    public function __construct(
        public string $problem_statement,
        public string $model,
        public int $max_tokens,
    ) {}
}
