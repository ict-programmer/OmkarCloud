<?php

namespace App\Data\Request\Claude;

use Spatie\LaravelData\Data;

class TextSummarizeData extends Data
{
    public function __construct(
        public string $text,
        public string $summary_length,
        public int $max_tokens,
        public ?string $model = null
    ) {}
}
