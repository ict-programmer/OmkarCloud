<?php

namespace App\Data\Request\Freepik;

use Spatie\LaravelData\Data;

class LoraCharacterTrainData extends Data
{
    public function __construct(
        public string $name,
        public string $quality,
        public string $gender,
        public array $images,
        public ?string $description,
    ) {}
}
