<?php

namespace App\Data\Request\Captions;

use Spatie\LaravelData\Data;

class AiTwinStatusData extends Data
{
    public function __construct(
        public string $operationId,
    ) {}
}
