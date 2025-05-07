<?php

namespace App\Data\Request\PremierPro;

use Spatie\LaravelData\Data;

class ImageGenData extends Data
{
    public function __construct(
        public string $prompt
    ) {}
}
