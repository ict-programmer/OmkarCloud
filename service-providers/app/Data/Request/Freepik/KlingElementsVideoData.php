<?php

namespace App\Data\Request\Freepik;

use App\Enums\Freepik\KlingElementModelEnum;
use Spatie\LaravelData\Data;

class KlingElementsVideoData extends Data
{
    public function __construct(
        public KlingElementModelEnum $model,
        public array $images,
        public ?string $prompt,
        public ?string $negative_prompt,
        public ?string $duration,
        public ?string $aspect_ratio,

    ) {}
}
