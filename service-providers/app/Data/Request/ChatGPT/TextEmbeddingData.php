<?php

namespace App\Data\Request\ChatGPT;

use Spatie\LaravelData\Data;

class TextEmbeddingData extends Data
{
    public function __construct(
        public string $model,
        public string $input,
    ) {}
}
