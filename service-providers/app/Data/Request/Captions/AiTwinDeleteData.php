<?php

namespace App\Data\Request\Captions;

use Spatie\LaravelData\Data;

class AiTwinDeleteData extends Data
{
    public function __construct(
        public string $name,
    ) {}
}
