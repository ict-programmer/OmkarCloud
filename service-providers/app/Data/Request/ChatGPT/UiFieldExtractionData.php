<?php

namespace App\Data\Request\ChatGPT;

use Spatie\LaravelData\Data;

class UiFieldExtractionData extends Data
{
    public function __construct(
        public string $image,
    ) {}
}
