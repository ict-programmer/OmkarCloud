<?php

namespace App\Data\Request\Captions;

use Spatie\LaravelData\Data;

class AiAdsSubmitData extends Data
{
    public function __construct(
        public string $script,
        public string $creatorName,
        public array $mediaUrls,
        public ?string $resolution,
    ) {}
}
