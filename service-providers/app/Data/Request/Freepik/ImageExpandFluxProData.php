<?php

namespace App\Data\Request\Freepik;

use Spatie\LaravelData\Data;

class ImageExpandFluxProData extends Data
{
    public function __construct(
        public string $image,
        public ?string $prompt = null,
        public ?int $left = null,
        public ?int $right = null,
        public ?int $top = null,
        public ?int $bottom = null,
    ) {}
}
