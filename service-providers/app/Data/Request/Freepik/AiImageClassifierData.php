<?php

namespace App\Data\Request\Freepik;

use Spatie\LaravelData\Data;

class AiImageClassifierData extends Data
{
    public function __construct(
        public string $image_url
    ) {}
}
