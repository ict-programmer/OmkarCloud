<?php

namespace App\Data\Request\Claude;

use Spatie\LaravelData\Data;

class TextClassifyData extends Data
{
    public function __construct(
        public string $text,
        public string $categories,
        public ?string $model = null
    ) {}
}
