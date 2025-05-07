<?php

namespace App\Data\Request\Gemini;

use Spatie\LaravelData\Data;

class DocumentSummarizationData extends Data
{
    public function __construct(
        public string $document_text,
        public string $model,
        public int $summary_length,
    ) {}
}
