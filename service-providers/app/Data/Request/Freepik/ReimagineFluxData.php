<?php

namespace App\Data\Request\Freepik;

use Spatie\LaravelData\Data;

class ReimagineFluxData extends Data
{
    public function __construct(
        public string $image,
        public ?string $prompt = null,
        public ?string $imagination = null,
        public ?string $aspect_ratio = null,
    ) {}
}
