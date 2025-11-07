<?php

namespace App\Data\Request\DeepSeek;

use Spatie\LaravelData\Data;

class DocumentQaData extends Data
{
    public function __construct(
        public string $document_text,
        public string $question,
    ) {}
}
