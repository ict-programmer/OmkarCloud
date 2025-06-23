<?php

namespace App\Data\Request\Freepik;

use App\Enums\Freepik\KlingModelEnum;
use Spatie\LaravelData\Data;

class KlingElementsVideoData extends Data
{
    public function __construct(
        public KlingModelEnum $model,
        public array $images,
        public ?string $prompt,
        public ?string $negative_prompt,
        public ?string $duration,
        public ?string $aspect_ratio,

    ) {}
}
