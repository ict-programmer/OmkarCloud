<?php

namespace App\Data\Request\Claude;

use Spatie\LaravelData\Data;

class TextSummarizeData extends Data
{
    public function __construct(
        public string $text,
        public string $summary_length,
        public ?string $model = null
    ) {}
}
