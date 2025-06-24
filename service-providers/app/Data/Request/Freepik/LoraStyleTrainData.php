<?php

namespace App\Data\Request\Freepik;

use Spatie\LaravelData\Data;

class LoraStyleTrainData extends Data
{
    public function __construct(
        public string $name,
        public string $quality,
        public array $images,
        public ?string $description = null,
    ) {}
}
