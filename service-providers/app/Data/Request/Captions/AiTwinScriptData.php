<?php

namespace App\Data\Request\Captions;

use Spatie\LaravelData\Data;

class AiTwinScriptData extends Data
{
    public function __construct(
        public ?string $language,
    ) {}
}
