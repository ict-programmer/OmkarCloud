<?php

namespace App\Data\GettyImages;

use Spatie\LaravelData\Data;

class VideoSearchCreativeByImageData extends Data
{
    public function __construct(
        public string $phrase,
        public ?array $fields,
    ) {}
}
