<?php

namespace App\Data\Request\Captions;

use Spatie\LaravelData\Data;

class AiCreatorSubmitData extends Data
{
    public function __construct(
        public string $script,
        public ?string $creatorName,
        public ?string $resolution,
    ) {}
}
