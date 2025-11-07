<?php

namespace App\Data\Request\Freepik;

use Spatie\LaravelData\Data;

class Imagen3GenerateData extends Data
{
    public function __construct(
        public string $prompt,
        public ?string $webhook_url = null,
        public ?int $num_images = 1,
        public ?string $aspect_ratio = null,
        public ?array $styling = null,
        public ?string $person_generation = null,
        public ?string $safety_settings = null,
    ) {}
}
