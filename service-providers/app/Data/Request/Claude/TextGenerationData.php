<?php

namespace App\Data\Request\Claude;

use Spatie\LaravelData\Data;

class TextGenerationData extends Data
{
    public function __construct(
        public string $prompt,
        public int $max_tokens,
        public ?string $model = null
    ) {}
}
