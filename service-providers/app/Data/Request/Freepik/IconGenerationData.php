<?php

namespace App\Data\Request\Freepik;

use Spatie\LaravelData\Data;

class IconGenerationData extends Data
{
    public function __construct(
        public string $prompt,
    ) {}
}
