<?php

namespace App\Data\Request\Perplexity;

use Spatie\LaravelData\Data;

class CodeAssistantData extends Data
{
    public function __construct(
        public string $query,
        public ?string $programming_language,
        public ?string $code_length,
        public ?string $model = 'sonar-reasoning',
    ) {}
}
