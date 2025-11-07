<?php

namespace App\Data\Request\Freepik;

use Spatie\LaravelData\Data;

class FluxDevGenerateData extends Data
{
    public function __construct(
        public string $prompt,
        public ?string $aspect_ratio,
        public ?int $seed,
        public ?array $styling = [],
    ) {}
}
