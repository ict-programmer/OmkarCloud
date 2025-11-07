<?php

namespace App\Data\Request\Claude;

use Spatie\LaravelData\Data;

class TextTranslateData extends Data
{
    public function __construct(
        public string $text,
        public string $source_language,
        public string $target_language,
        public int $max_tokens,
        public ?string $model = null
    ) {}
}
